<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterion;
use Illuminate\Support\Facades\Schema;

class ForUser implements ICriterion
{
    protected $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function apply($model)
    {
        if (Schema::hasColumn($model->getTable(), 'owner_id'))
            return $model->where('owner_id', $this->user_id);
        return $model->where('user_id', $this->user_id);
    }
}
