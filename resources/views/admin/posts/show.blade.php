@extends('layouts.admin')
@section('content')
    <section class="container">
        <h1>{{$post->title}}</h1>
        <p>{{ $post->body}}</p>
        <span>{{$post->category ? $post->category->name : 'Uncategorized'}}</span>
        	{{-- $post->category?->name --}}
    </section>
@endsection
