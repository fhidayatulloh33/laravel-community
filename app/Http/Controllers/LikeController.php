<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike(Request $request)
    {
        $validated = $request->validate([
            'likeable_id' => 'required|integer',
            'likeable_type' => 'required|string|in:post,comment',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $type = $request->likeable_type === 'post' ? Post::class : Comment::class;
        $likeable_id = $request->likeable_id;

        $existingLike = Like::where('user_id', $user->id)
            ->where('likeable_type', $type)
            ->where('likeable_id', $likeable_id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            Like::create([
                'user_id' => $user->id,
                'likeable_type' => $type,
                'likeable_id' => $likeable_id,
            ]);
            $liked = true;
        }

        $likeCount = $type::findOrFail($likeable_id)->likes()->count();

        return response()->json(['liked' => $liked, 'likeCount' => $likeCount]);
    }
}
