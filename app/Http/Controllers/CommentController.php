<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Simpan komentar baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'comment' => 'required|string',
            'post_id' => 'required|exists:posts,id'
        ]);

        Comment::create([
            'comment' => $validated['comment'],
            'post_id' => $validated['post_id'],
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    // Form edit komentar
    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment); // pastikan user berhak edit
        return view('comments.edit', compact('comment'));
    }

    // Update komentar
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        $validated = $request->validate([
            'comment' => 'required|string',
        ]);

        $comment->update([
            'comment' => $validated['comment']
        ]);

        return redirect()->route('posts.show', $comment->post_id)->with('success', 'Komentar berhasil diperbarui.');
    }

    // Hapus komentar
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $postId = $comment->post_id;
        $comment->delete();

        return redirect()->route('posts.show', $postId)->with('success', 'Komentar berhasil dihapus.');
    }
}
