<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface IVendor
{
    public function findByEmail($email);
    public function search(Request $request);
}
