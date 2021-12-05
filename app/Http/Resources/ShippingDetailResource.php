<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'address' => $this->address,
            'landmark' => $this->landmark,
            'description' => $this->description,
            'phone' => $this->phone,
            'second_phone' => $this->second_phone,
            'state' => $this->state,
            'city' => $this->city,
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
