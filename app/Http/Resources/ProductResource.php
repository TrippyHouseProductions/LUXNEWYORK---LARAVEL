<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'image'       => $this->image,
            'category'    => new CategoryResource($this->whenLoaded('category')),
            'created_at'  => $this->created_at,
            'stock'       => $this->stock,
            'sku'         => $this->sku,
        ];
    }
}