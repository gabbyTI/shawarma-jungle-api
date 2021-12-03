<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterion;

class IsActiveVendor implements ICriterion
{

    public function apply($model)
    {
        return $model->where('isActive', true);
    }
}
