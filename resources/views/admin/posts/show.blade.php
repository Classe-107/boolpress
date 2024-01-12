@extends('layouts.app')
@section('content')
    <section class="container">
        <h1>{{$post->title}}</h1>
        <p>{{ $post->body}}</p>
    </section>
@endsection
