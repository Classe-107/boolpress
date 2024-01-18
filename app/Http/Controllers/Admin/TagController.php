<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $formData = $request->validated();
        //CREATE SLUG
        $slug = Str::of($formData['name'])->slug('-');
        //add slug to formData
        $formData['slug'] = $slug;
        $tag = Tag::create($formData);
        return redirect()->route('admin.tags.show', $tag->slug);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        return view('admin.tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        $currentUserId = Auth::id();
        if ($currentUserId != 1) {
            abort(403);
        }
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $formData = $request->validated();
        $formData['slug'] = $tag->slug;

        if ($tag->name !== $formData['name']) {
            //CREATE SLUG
            $slug = Str::of($formData['name'])->slug('-');
            $formData['slug'] = $slug;
        }
        $tag->update($formData);
        return redirect()->route('admin.tags.show', $tag->slug);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $currentUserId = Auth::id();
        if ($currentUserId != 1) {
            abort(403);
        }
        $tag->delete();
        return to_route('admin.tags.index')->with('message', "$tag->name eliminato con successo");
    }
}
