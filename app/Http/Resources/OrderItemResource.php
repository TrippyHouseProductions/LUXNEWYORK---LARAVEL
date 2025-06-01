<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'order_id'  => $this->order_id,
            'product_id'=> $this->product_id,
            'quantity'  => $this->quantity,
            'price'     => $this->price,
            'product'   => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
