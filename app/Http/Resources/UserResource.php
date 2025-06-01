<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// class UserResource extends JsonResource
// {
//     public function toArray($request)
//     {
//         return [
//             'id'         => $this->id,
//             'name'       => $this->name,
//             'email'      => $this->email,
//             'user_type'  => $this->user_type,
//             'created_at' => $this->created_at,
//         ];
//     }
// }

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'email'        => $this->email,
            'user_type'    => $this->user_type,
            'created_at'   => $this->created_at,
            'orders_count' => $this->orders_count ?? 0,
            'orders_total' => $this->orders_total ?? 0, // if using withSum, this is the alias
        ];
    }
}


