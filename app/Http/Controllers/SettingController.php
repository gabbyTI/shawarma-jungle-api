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

/**
 * @group Profile Management
 */
class SettingController extends Controller
{
    protected $users;
    protected $vendors;

    public function __construct(IUser $users, IVendor $vendors)
    {
        $this->users = $users;
        $this->vendors = $vendors;
    }

    /**
     * Updating user profile
     *
     * @authenticated
     *
     * Used to update the details of the currently authenticated user.
     *
     * @bodyParam first_name string required The first name of the user. Example: John.
     * @bodyParam last_name string required The last name of the user. Example: Doe
     *
     * @responseFile 200 storage\responses\update.user.profile.json
     */
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

    /**
     * Change user password
     *
     * @authenticated
     *
     * Used to update the password of the currently logged in user.
     *
     * @bodyParam old_password string required
     * @bodyParam password string required
     * @bodyParam password_confirmation string required
     *
     * @responseFile 200 storage\responses\update.password.json
     *
     *
     */
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


    /**
     * Updating vendor profile
     *
     * @authenticated
     *
     * Used to update the details of the currently authenticated vendor.
     *
     * @bodyParam business_name string required Name of the business/vendor. Example: Tasty Shawarma
     * @bodyParam manager_full_name string required first and last name of the manager. Example: John Doe
     * @bodyParam manager_phone string required manager/vendor's phone number. Example: 08123456789
     * @bodyParam bank_name string required Name of bankused by vendor. Example: Doe
     * @bodyParam bank_account_name string required Vendor's bank account name. Example: Tasty Shawarma LTD
     * @bodyParam bank_account_number string required Vendor's bank account number. Example: Doe
     * @bodyParam address string required Vendor's Formatted address from google maps. Example: Doe
     * @bodyParam location object required the latitude and longitude from the google maps api Example: {"latitude": 4.232423234, "longitude": 5.423242343}

     * @responseFile 200 storage\responses\update.vendor.profile.json
     *
     */
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

    /**
     * Change vendor password
     *
     * @authenticated
     *
     * Used to update the password of the currently logged in vendor
     *
     * @bodyParam old_password password required.
     * @bodyParam password password required.
     * @bodyParam password_confirmation password required.
     *
     * @responseFile 200 storage\responses\update.password.json
     *
     *
     */

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
