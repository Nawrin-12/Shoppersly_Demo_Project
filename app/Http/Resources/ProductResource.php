<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    
public function toArray($request)
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'description' => $this->description,
        'category' => $this->category,
        'price' => $this->price,
        'url' => $this->url,
        'status' => $this->status->value,
        'created_at' => $this->created_at->toDateTimeString(),
        'images' => ProductImageResource::collection($this->whenLoaded('images')),
    ];
}
}
