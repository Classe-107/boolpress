<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // if($request->query('category')) {
        //     $posts = Post::where('category_id', $request('category'))->paginate(3);
        // } else {
        //     $posts = Post::latest()->paginate(3);
        // }
        $posts = Post::paginate(3);
        return response()->json(
            [
                'success' => true,
                'results' => $posts
            ]
        );
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->with(['category', 'tags'])->first();
        return response()->json(
            [
                'success' => true,
                'results' => $post
            ]
        );
    }
}
