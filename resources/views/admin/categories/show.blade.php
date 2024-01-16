@extends('layouts.admin')
@section('content')
    <section class="container">
        <h1>{{$category->name}}</h1>
        <h3>Post List</h3>
        <ul>
            @forelse ($category->posts as $post)
                <li> {{$post->title}}</li>
            @empty
                <li>No posts</li>
            @endforelse
        </ul>
    </section>
@endsection
