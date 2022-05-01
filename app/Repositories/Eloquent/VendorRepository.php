<?php

namespace App\Repositories\Eloquent;

use App\Models\Vendor;
use App\Repositories\Contracts\IVendor;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Parser\QueryString;
use Grimzy\LaravelMysqlSpatial\Types\Point;

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

    public function within(Request $request)
    {
        $query = (new $this->model)->newQuery();

        $query->where('isActive', true);
        // returns only vendors who have products by default
        $query->has('products');

        $lat = $request->latitude;
        $lng = $request->longitude;
        $dist = $request->distance;
        $unit = $request->unit;

        if ($lat && $lng) {
            $point = new Point($lat, $lng);
            $unit == 'km' ? $dist *= 1000 : $dist *= 1609.32;
            $query->distanceSphereExcludingSelf('location', $point, $dist);
        }

        if ($request->orderByLastest) {
            $query->latest();
        } else {
            $query->oldest();
        }

        return $query->get();
    }
}
