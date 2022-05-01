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
            'paymentType' => $this->payment_type,
            'shippingMethod' => $this->delivery_type,
            'status' => $this->status,
            'orderItems' => $this->order_products,
            'shippipingDetails' => new ShippingDetailResource($this->whenLoaded('shippingDetail')),
            "vendor" => new VendorResource($this->whenLoaded('vendor')),
            "user" => new UserResource($this->whenLoaded('user')),
            'createDates' => [
                'creadted_at_human' => $this->created_at->diffForHumans(),
                'creadted_at' => $this->created_at,
            ]
        ];
    }
}
