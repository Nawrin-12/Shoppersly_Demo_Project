<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $order = $this->resource; 

        return [
            'id' => $order->id,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'product_details' => $order->product_details,
            'images' => $this->whenLoaded('images', function () use ($order) {
                return $order->images->map(function ($image) {
                    return asset('storage/' . $image->image_path);
                });
            }),
            'created_at' => $order->created_at->toDateTimeString(),
        ];
    }
}
