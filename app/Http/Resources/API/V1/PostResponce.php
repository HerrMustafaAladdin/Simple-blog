<?php

namespace App\Http\Resources\API\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResponce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            =>  $this->id,
            'Title'         =>  $this->title,
            'Category'      =>  new CategoryResponce($this->whenLoaded('category')),
            'tags'          =>  TagResponce::collection($this->whenLoaded('tags')),
            'Primary image' =>  asset('storage/'. env('IMAGE_UPLOAD_PATH') . '/posts/'.$this->primary_image),
            'images'        =>  PostImageResponce::collection($this->whenLoaded('images')),
            'Content'       =>  $this->content,
            'is_active'     =>  $this->is_active,
            'Written by'    =>  new UserResponce($this->whenLoaded('user')),
            'Post creation date'    =>  $this->created_at->format('Y/m/d')
        ];
    }
}
