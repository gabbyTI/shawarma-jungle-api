<?php

namespace App\Repositories\Contracts;

interface IVendor
{
    public function findByEmail($email);
}
