<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ShippingDetail;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::factory()
            ->count(10)
            ->hasShippingDetails(1)
            ->create();

        Vendor::factory()
            ->count(10)
            ->hasProducts(4)
            ->create();
    }
}
