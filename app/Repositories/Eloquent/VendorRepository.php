<?php

namespace App\Repositories\Eloquent;

use App\Models\Vendor;
use App\Repositories\Contracts\IVendor;

class VendorRepository extends BaseRepository implements IVendor
{
    public function model()
    {
        return Vendor::class;
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }
}
