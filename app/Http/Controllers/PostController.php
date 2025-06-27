<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // List semua post
    public function index(Request $request)
    {
        $query = Post::with('tags', 'likes');

        if ($request->has('tag') && $request->tag != '') {
            $tag = $request->tag;
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('name', $tag);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(10);
        $allTags = \App\Models\Tag::all();

        return view('posts.index', compact('posts', 'allTags'));
    }

    // Form buat post baru
    public function create()
    {
        $tags = Tag::all();
        return view('posts.create', compact('tags'));
    }

    // Simpan post baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $post = new Post([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => Auth::id(),
        ]);
        $post->save();

        if (!empty($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return redirect()->route('posts.show', $post)->with('success', 'Post berhasil dibuat.');
    }

    // Detail post
    public function show(Post $post)
    {
        $post->load('tags','comments.user','user');
        return view('posts.show', compact('post'));
    }

    // Form edit post
    public function edit(Post $post)
    {
        $this->authorize('update', $post); // Pastikan user berhak edit
        $tags = Tag::all();
        $postTags = $post->tags->pluck('id')->toArray();
        return view('posts.edit', compact('post','tags','postTags'));
    }

    // Update post
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post); // Pastikan user berhak edit
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        $post->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('posts.show', $post)->with('success', 'Post berhasil diperbarui.');
    }

    // Hapus post
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post); // Pastikan user berhak hapus
        $post->tags()->detach();
        $post->comments()->delete();
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post berhasil dihapus.');
    }
}
