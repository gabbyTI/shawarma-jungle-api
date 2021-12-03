<?php

namespace App\Repositories\Eloquent;

use App\Models\ShippingDetail;
use App\Repositories\Contracts\IShippingDetail;

class ShippingDetailRepository extends BaseRepository implements IShippingDetail
{
    public function model()
    {
        return ShippingDetail::class;
    }
}
