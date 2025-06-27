@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-md">
    <h1 class="text-2xl font-bold mb-4">Edit Komentar</h1>

    @if ($errors->any())
    <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
        <ul>
            @foreach ($errors->all() as $error)
                <li class="list-disc ml-5">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <textarea name="content" rows="5" class="w-full border rounded px-3 py-2 mb-4" required>{{ old('content', $comment->content) }}</textarea>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Komentar</button>
        <a href="{{ url()->previous() }}" class="ml-4 text-gray-600 hover:underline">Batal</a>
    </form>
</div>
@endsection
