<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'shippiping_details' => ShippingDetailResource::collection($this->whenLoaded('shippingDetails')),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'create_dates' => [
                'creadted_at_human' => $this->created_at->diffForHumans(),
                'creadted_at' => $this->created_at,
            ],
            "update_dates" => [
                "updated_at_human" => $this->updated_at->diffForHumans(),
                "updated_at" => $this->updated_at,
            ],
        ];
    }
}
