@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-3xl">
    <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>

    <div class="mb-6 border-b pb-4">
        <p class="whitespace-pre-wrap">{{ $post->content }}</p>

        <div class="mt-4">
            <strong>Tags:</strong>
            @foreach($post->tags as $tag)
                <span class="inline-block bg-gray-300 px-2 py-1 rounded mr-2">{{ $tag->name }}</span>
            @endforeach
        </div>
    </div>

    {{-- Form tambah komentar --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Tambah Komentar</h2>

        @if ($errors->any())
        <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="list-disc ml-5">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('comments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">

            <textarea name="content" rows="4" class="w-full border rounded px-3 py-2 mb-2" placeholder="Tulis komentar Anda..." required>{{ old('content') }}</textarea>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Kirim Komentar</button>
        </form>
    </div>

    {{-- List komentar --}}
    <div>
        <h2 class="text-xl font-semibold mb-4">Komentar ({{ $post->comments->count() }})</h2>

        @forelse($post->comments as $comment)
        <div class="mb-4 p-4 border rounded bg-gray-50">
            <p class="whitespace-pre-wrap mb-2">{{ $comment->content }}</p>

            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                <span>Dikirim {{ $comment->created_at->diffForHumans() }}</span>
                <div>
                    <a href="{{ route('comments.edit', $comment->id) }}" class="text-blue-600 mr-4 hover:underline">Edit</a>

                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin hapus komentar ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <p class="text-gray-500">Belum ada komentar.</p>
        @endforelse
    </div>
</div>
@endsection
