<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterion;

class ForVendor implements ICriterion
{
    protected $vendor_id;

    public function __construct($vendor_id)
    {
        $this->vendor_id = $vendor_id;
    }

    public function apply($model)
    {
        return $model->where('vendor_id', $this->vendor_id);
    }
}
