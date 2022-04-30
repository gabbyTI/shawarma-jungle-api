<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'id' => $this['id'],
            // 'modelType' => $this['modelType'],
            'productId' => $this['modelId'],
            'name' => $this['name'],
            'price' => $this['price'],
            'image' => Product::find($this['modelId'])->images['thumbnail'],
            'quantity' => $this['quantity'],
        ];
    }
}
