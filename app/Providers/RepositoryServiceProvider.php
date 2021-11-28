<?php

namespace App\Providers;

use App\Repositories\Contracts\IProduct;
use App\Repositories\Contracts\IUser;
use App\Repositories\Contracts\IVendor;
use App\Repositories\Eloquent\ProductRepository;
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
    }
}
