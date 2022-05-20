<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\ITransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $tranactions;

    public function __construct(ITransaction $tranactions)
    {
        $this->tranactions = $tranactions;
    }
}
