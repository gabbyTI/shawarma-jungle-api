<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
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
            'business_name' => $this->business_name,
            'manager_full_name' => $this->manager_full_name,
            'manager_phone' => $this->manager_phone,
            'email' => $this->email,
            'business_address' => $this->address,
            'business_bank_name' => $this->bank_name,
            'business_account_number' => $this->bank_account_number,
            'business_account_name' => $this->bank_account_name,
            "isActive" => $this->isActive == 0 ? false : true,
            "location" => $this->location,
            'products' =>  ProductResource::collection($this->whenLoaded('products')),
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
