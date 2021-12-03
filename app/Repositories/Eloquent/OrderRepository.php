<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Contracts\IOrder;

class OrderRepository extends BaseRepository implements IOrder
{
    public function model()
    {
        return Order::class;
    }
}
