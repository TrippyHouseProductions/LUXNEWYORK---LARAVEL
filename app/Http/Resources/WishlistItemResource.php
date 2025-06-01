<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WishlistItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'      => $this->id,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}

