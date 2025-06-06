<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'items' => CartItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
