<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'subtotal' => $this['subtotal'],
            'discount' => $this['discount'],
            'discountPercentage' => $this['discountPercentage'],
            'couponId' => $this['couponId'],
            'shippingCharges' => $this['shippingCharges'],
            'netTotal' => $this['netTotal'],
            'tax' => $this['tax'],
            'total' => $this['total'],
            'roundOff' => $this['roundOff'],
            'payable' => $this['payable'],
            'items' => CartItemResource::collection($this['items'])
        ];

        // return [
        //     'subtotal' => $this->subtotal,
        //     'discount' => $this->discount,
        //     'discountPercentage' => $this->discountPercentage,
        //     'couponId' => $this->couponId,
        //     'shippingCharges' => $this->shippingCharges,
        //     'netTotal' => $this->netTotal,
        //     'tax' => $this->tax,
        //     'total' => $this->total,
        //     'roundOff' => $this->roundOff,
        //     'payable' => $this->payable,
        //     'items' => $this->items
        // ];
    }
}
