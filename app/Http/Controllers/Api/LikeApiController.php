<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Submit like for post or comment
    public function like(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:post,comment',
            'id' => 'required|integer',
        ]);

        $user = Auth::user();

        // Check if already liked
        $existingLike = Like::where('user_id', $user->id)
            ->where('likeable_id', $data['id'])
            ->where('likeable_type', $data['type'] === 'post' ? Post::class : Comment::class)
            ->first();

        if ($existingLike) {
            return response()->json(['message' => 'Already liked'], 400);
        }

        // Create like
        $like = new Like();
        $like->user_id = $user->id;
        $like->likeable_id = $data['id'];
        $like->likeable_type = $data['type'] === 'post' ? Post::class : Comment::class;
        $like->save();

        return response()->json(['message' => 'Liked successfully']);
    }
}
