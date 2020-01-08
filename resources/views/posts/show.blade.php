@extends('layout')

@section('content')
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->content }}</p>
    <p>Added at: {{ $post->created_at->diffForHumans() }}</p>

    @if ((new Carbon\Carbon())->diffInMinutes($post->created_at) < 5)
        <strong>New!</strong>
    @endif
    <h4>Comments</h4>
    @forelse ($post->comments as $comment)
        <p class="text-muted">
            {{ $comment->content }}, added {{ $comment->created_at->diffForHumans() }}
        </p>
    @empty
        <p>No comments yet!</p>
    @endforelse
@endsection
