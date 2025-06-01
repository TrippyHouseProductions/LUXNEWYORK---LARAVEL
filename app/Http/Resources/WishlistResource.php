<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'items' => WishlistItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
