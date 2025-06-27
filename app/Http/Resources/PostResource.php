<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'tags' => $this->tags->pluck('name'),
            'likes_count' => $this->likes()->count(),
            'comments_count' => $this->comments()->count(),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
