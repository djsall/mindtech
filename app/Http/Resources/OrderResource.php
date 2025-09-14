<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customerId' => $this->user_id,
            'restaurantId' => $this->restaurant_id,
            'status' => $this->status,
            'items' => $this->items,
        ];
    }
}
