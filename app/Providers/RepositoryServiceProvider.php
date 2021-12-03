<?php

namespace App\Providers;

use App\Repositories\Contracts\IOrder;
use App\Repositories\Contracts\IProduct;
use App\Repositories\Contracts\IShippingDetail;
use App\Repositories\Contracts\IUser;
use App\Repositories\Contracts\IVendor;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\ShippingDetailRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\VendorRepository;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IUser::class, UserRepository::class);
        $this->app->bind(IVendor::class, VendorRepository::class);
        $this->app->bind(IProduct::class, ProductRepository::class);
        $this->app->bind(IOrder::class, OrderRepository::class);
        $this->app->bind(IShippingDetail::class, ShippingDetailRepository::class);
    }
}
