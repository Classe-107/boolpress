@extends('layouts.admin')
@section('content')
    <section class="container my-3" id="item-post">
        <div class="d-flex justify-content-between align-items-center">
             <h1>{{$post->title}}</h1>
             <a href="{{route('admin.posts.edit', $post->slug)}}" class="btn btn-success px-3">Edit</a>
        </div>
        <div>
            <p>{!! $post->body !!}</p>
            @if($post->category_id)
                <div class="mb-3">
                    <h4>Category</h4>
                    <a class="badge text-bg-primary" href="{{route('admin.categories.show', $post->category->slug)}}">{{$post->category->name}}</a>
                </div>
            @endif
            <img src="{{asset('storage/' . $post->image)}}" alt="{{$post->title}}">
             @if(count($post->tags) > 0)
                <div class="mb-3">
                    <h4>Tags</h4>
                    @foreach ($post->tags as $tag)
                        <a class="badge rounded-pill text-bg-success" href="{{route('admin.tags.show', $tag->slug)}}">{{$tag->name}}</a>
                    @endforeach

                </div>
            @endif
        </div>
    </section>
@endsection
