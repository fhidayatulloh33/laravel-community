<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;

class PostApiController extends Controller
{
    // Middleware sanctum untuk proteksi route API ini
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Get all posts with pagination
    public function index(Request $request)
    {
        $posts = Post::with('tags', 'likes', 'comments')->paginate(10);
        return PostResource::collection($posts);
    }

    // Get comments of a specific post
    public function comments($postId)
    {
        $post = Post::findOrFail($postId);
        $comments = $post->comments()->with('likes')->get();
        return CommentResource::collection($comments);
    }
}
