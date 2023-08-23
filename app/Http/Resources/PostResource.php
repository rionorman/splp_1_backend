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
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'username' =>  $this->username->name,
            'cat_id' => $this->cat_id,
            'category' => $this->category->category,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image,
            'updated_at' => $this->updated_at,
        ];
    }
}
