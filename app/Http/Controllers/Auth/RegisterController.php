<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\VendorResource;
use App\Repositories\Contracts\IUser;
use App\Repositories\Contracts\IVendor;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request as HttpRequest;
use Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;


    protected $users;
    protected $vendors;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IUser $users, IVendor $vendors)
    {
        $this->users = $users;
        $this->vendors = $vendors;
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // dd(Auth::getDefaultDriver());
        $middlewareDriver = Auth::getDefaultDriver();
        switch ($middlewareDriver) {
            case 'user-api':
                return Validator::make($data, [
                    'first_name' => ['required', 'string', 'max:255'],
                    'last_name' => ['required', 'string', 'max:255'],
                    'phone' => ['required', 'string', 'max:15'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ]);
                break;

            case 'vendor-api':
                return Validator::make($data, [
                    'business_name' => ['required', 'string', 'max:255'],
                    'manager_full_name' => ['required', 'string', 'max:255'],
                    'manager_phone' => ['required', 'string', 'max:15'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:vendors'],
                    'address' => ['required', 'string', 'max:255'],
                    'bank_name' => ['required', 'string', 'max:255'],
                    'bank_account_number' => ['required', 'string', 'max:10'],
                    'bank_account_name' => ['required', 'string', 'max:255'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ]);
                break;
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $middlewareDriver = Auth::getDefaultDriver();
        switch ($middlewareDriver) {
            case 'user-api':
                return $this->users->create([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);
                break;

            case 'vendor-api':
                return $this->vendors->create([
                    'business_name' => $data['business_name'],
                    'manager_full_name' => $data['manager_full_name'],
                    'manager_phone' => $data['manager_phone'],
                    'email' => $data['email'],
                    'address' => $data['address'],
                    'bank_name' => $data['bank_name'],
                    'bank_account_number' => $data['bank_account_number'],
                    'bank_account_name' => $data['bank_account_name'],
                    'password' => Hash::make($data['password']),
                ]);
                break;
        }
    }

    protected function registered(HttpRequest $request, $user)
    {
        $middlewareDriver = Auth::getDefaultDriver();
        switch ($middlewareDriver) {
            case 'user-api':
                return ApiResponder::successResponse("Registration successful", new UserResource($user));
                break;

            case 'vendor-api':
                return ApiResponder::successResponse("Registration successful", new VendorResource($user));
                break;
        }
    }
}
