@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-md">
    <h1 class="text-2xl font-bold mb-4">Edit Post</h1>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('posts.update', $post->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="title" class="block font-medium mb-1">Judul:</label>
        <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" class="w-full border rounded px-3 py-2 mb-4" required>

        <label for="content" class="block font-medium mb-1">Konten:</label>
        <textarea name="content" id="content" rows="5" class="w-full border rounded px-3 py-2 mb-4" required>{{ old('content', $post->content) }}</textarea>

        <label class="block font-medium mb-1">Tags:</label>
        <div class="mb-4">
            @foreach($tags as $tag)
                <label class="inline-flex items-center mr-4">
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="mr-1"
                        {{ (is_array(old('tags', $post->tags->pluck('id')->toArray())) && in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray()))) ? 'checked' : '' }}>
                    {{ $tag->name }}
                </label>
            @endforeach
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        <a href="{{ route('posts.index') }}" class="ml-4 text-gray-600 hover:underline">Batal</a>
    </form>
</div>
@endsection
