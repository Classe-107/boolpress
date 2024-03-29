<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUserId = Auth::id();
        if ($currentUserId == 1) {
            $posts = Post::paginate(3);
        } else {
            $posts = Post::where('user_id', $currentUserId)->paginate(3);
        }

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {

        $formData = $request->validated();
        //CREATE SLUG
        $slug = Post::getSlug($formData['title']);
        //add slug to formData
        $formData['slug'] = $slug;
        //prendiamo l'id dell'utente loggato
        $userId = Auth::id();
        //dd($userId);
        //aggiungiamo l'id dell'utente
        $formData['user_id'] = $userId;
        if ($request->hasFile('image')) {
            $path = Storage::put('images', $formData['image']);
            $formData['image'] = $path;
        }
        $post = Post::create($formData);
        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }
        return redirect()->route('admin.posts.show', $post->slug);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $currentUserId = Auth::id();
        if ($currentUserId == $post->user_id || $currentUserId == 1) {
            return view('admin.posts.show', compact('post'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $currentUserId = Auth::id();
        if ($currentUserId != $post->user_id && $currentUserId != 1) {
            abort(403);
        }
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $currentUserId = Auth::id();
        if ($currentUserId != $post->user_id && $currentUserId != 1) {
            abort(403);
        }
        $formData = $request->validated();
        $formData['slug'] = $post->slug;
        if ($post->title !== $formData['title']) {
            //CREATE SLUG
            $slug = Post::getSlug($formData['title']);
            $formData['slug'] = $slug;
        }

        //aggiungiamo l'id dell'utente proprietario del post
        $formData['user_id'] = $post->user_id;
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::delete($post->image);
            }

            $path = Storage::put('images', $formData['image']);
            $formData['image'] = $path;
        }
        $post->update($formData);
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('admin.posts.show', $post->slug);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $currentUserId = Auth::id();
        if ($currentUserId != $post->user_id && $currentUserId != 1) {
            abort(403);
        }
        // $post->tags()->detach(); //non necessario se cascadeOnDelete
        if ($post->image) {
            Storage::delete($post->image);
        }

        $post->delete();
        return to_route('admin.posts.index')->with('message', "$post->title eliminato con successo");
    }
}
