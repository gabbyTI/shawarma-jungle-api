<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'payment_type' => $this->payment_type,
            'shipping_method' => $this->delivery_type,
            'order_items' => $this->order_products,
            "shipping_details" => '<RETURN SHIPPING DETAILS>',
            "vendor" => new VendorResource($this->whenLoaded('vendor')),
            "user" => new VendorResource($this->whenLoaded('user')),
            'create_dates' => [
                'creadted_at_human' => $this->created_at->diffForHumans(),
                'creadted_at' => $this->created_at,
            ]
        ];
    }
}
