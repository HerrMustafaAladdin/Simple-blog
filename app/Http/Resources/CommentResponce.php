<?php

namespace App\Http\Resources;

use App\Http\Resources\API\V1\PostResponce;
use App\Http\Resources\API\V1\UserResponce;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResponce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user name'             =>  new UserResponce($this->whenLoaded('user')),
            'post details'          =>  new PostResponce($this->whenLoaded('post')),
            'status'                =>  $this->is_active,
            'content'               =>  $this->content,
            'answer comment'        =>  $this->parent_id == 0 || $this->whenLoaded('parent') || $this->whenLoaded('children') ? new CommentResponce($this->whenLoaded('parent')) : new CommentResponce($this->whenLoaded('children')),

        ];
    }
}
