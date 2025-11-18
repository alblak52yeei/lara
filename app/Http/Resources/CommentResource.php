<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'commentable_type' => $this->commentable_type,
            'commentable_id' => $this->commentable_id,
            'commentable' => $this->when($this->relationLoaded('commentable'), function () {
                if (!$this->commentable) {
                    return null;
                }
                if ($this->commentable_type === 'App\Models\Contact') {
                    return new ContactResource($this->commentable);
                }
                if ($this->commentable_type === 'App\Models\Category') {
                    return new CategoryResource($this->commentable);
                }
                return null;
            }),
            'user_id' => $this->user_id,
            'user' => $this->when($this->relationLoaded('user') && $this->user, function () {
                return new UserResource($this->user);
            }),
            'content' => $this->content,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'deleted_at' => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
