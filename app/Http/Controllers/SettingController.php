<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Http\Resources\UserResource;
use App\Http\Resources\VendorResource;
use App\Repositories\Contracts\IUser;
use App\Repositories\Contracts\IVendor;
use App\Rules\CheckSamePassword;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class SettingController extends Controller
{
    protected $users;
    protected $vendors;

    public function __construct(IUser $users, IVendor $vendors)
    {
        $this->users = $users;
        $this->vendors = $vendors;
    }

    public function updateUserProfile(Request $request)
    {
        $request->validate([
            "first_name" => ['required', 'string'],
            "last_name" => ['required', 'string'],
        ]);

        $user = $this->users->update(auth()->id(), [
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
        ]);

        return ApiResponder::successResponse("Updated", new UserResource($user));
    }

    public function updateUserPassword(Request $request)
    {

        $request->validate([
            'old_password' => ['required', new MatchOldPassword],
            'password' => ['required', 'confirmed', 'min:8', new CheckSamePassword],
        ]);

        $this->users->update(auth()->id(), [
            'password' => Hash::make($request->password)
        ]);


        return ApiResponder::successResponse("Password updated");
    }


    public function updateVendorProfile(Request $request)
    {
        $request->validate([
            "business_name" => ['required', 'string'],
            "manager_full_name" => ['required', 'string'],
            "manager_phone" => ['required', 'string', 'max:14'],
            "address" => ['required', 'string'],
            "bank_name" => ['required', 'string'],
            "bank_account_number" => ['required', 'string', 'max:10', 'min:10'],
            "bank_account_name" => ['required', 'string'],
            'location.latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'location.longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
        ]);

        $location = new Point($request->location['latitude'], $request->location['longitude']);

        $vendor = $this->vendors->update(auth()->id(), [
            "business_name" => $request->business_name,
            "manager_full_name" => $request->manager_full_name,
            "manager_phone" => $request->manager_phone,
            "address" => $request->address,
            "bank_name" => $request->bank_name,
            "bank_account_number" => $request->bank_account_number,
            "bank_account_name" => $request->bank_account_name,
            'location' => $location,
        ]);

        return ApiResponder::successResponse("Updated", new VendorResource($vendor));
    }

    public function updateVendorPassword(Request $request)
    {

        $request->validate([
            'old_password' => ['required', new MatchOldPassword],
            'password' => ['required', 'confirmed', 'min:8', new CheckSamePassword],
        ]);

        $this->vendors->update(auth()->id(), [
            'password' => Hash::make($request->password)
        ]);


        return ApiResponder::successResponse("Password updated");
    }
}
