@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Filter & Search Form -->
    <form method="GET" action="{{ route('posts.index') }}" class="mb-6 flex gap-3">
        <select name="tag" onchange="this.form.submit()" class="border rounded px-3 py-2">
            <option value="">Filter by Tag</option>
            @foreach($allTags as $tag)
                <option value="{{ $tag->name }}" {{ request('tag') == $tag->name ? 'selected' : '' }}>{{ $tag->name }}</option>
            @endforeach
        </select>

        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..." 
            class="border rounded px-3 py-2 flex-grow" />
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Search</button>
    </form>

    <!-- Posts List -->
    @foreach ($posts as $post)
        <div class="post border-b border-gray-300 py-4" data-id="{{ $post->id }}">
            <h2 class="text-xl font-bold mb-1">{{ $post->title }}</h2>
            <p class="mb-2">{{ Str::limit($post->content, 150) }}</p>

            <div class="tags mb-2">
                Tags: 
                @foreach($post->tags as $tag)
                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm mr-2">{{ $tag->name }}</span>
                @endforeach
            </div>

            <button 
                class="like-btn px-4 py-2 rounded border cursor-pointer"
                data-likeable-type="post"
                data-likeable-id="{{ $post->id }}"
                >
                @auth
                    {{ $post->isLikedBy(auth()->user()) ? 'Unlike' : 'Like' }} ( <span class="like-count">{{ $post->likes->count() }}</span> )
                @else
                    Like ( <span class="like-count">{{ $post->likes->count() }}</span> )
                @endauth
            </button>
        </div>
    @endforeach

    {{ $posts->withQueryString()->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.like-btn').forEach(button => {
            button.addEventListener('click', () => {
                const likeableId = button.getAttribute('data-likeable-id');
                const likeableType = button.getAttribute('data-likeable-type');

                axios.post("{{ route('likes.toggle') }}", {
                    likeable_id: likeableId,
                    likeable_type: likeableType,
                    _token: "{{ csrf_token() }}"
                }).then(response => {
                    if (response.data.liked) {
                        button.textContent = `Unlike (${response.data.likeCount})`;
                    } else {
                        button.textContent = `Like (${response.data.likeCount})`;
                    }
                }).catch(error => {
                    alert('Error in liking post. Please try again.');
                });
            });
        });
    });
</script>
@endsection
