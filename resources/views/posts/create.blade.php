@extends('layout')

@section('content')
<form action="{{ route('posts.store') }}" method="post">
    @csrf
    <p>
        <label for="">Title</label>
        <input type="text" name="title" value="{{ old('title') }}">
    </p>
    <p>
        <label for="">Content</label>
        <input type="text" name="content" value="{{ old('content') }}">
    </p>
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <button type="submit">Create!</button>
</form>
@endsection
