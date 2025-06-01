<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'user_id'           => $this->user_id,
            'status'            => $this->status,
            'total'             => $this->total,
            'fake_payment_info' => $this->fake_payment_info,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'user'              => $this->whenLoaded('user'),
            'order_items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
