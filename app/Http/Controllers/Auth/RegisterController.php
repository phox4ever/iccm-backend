<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RegistrableTrait;
use App\Models\Accommodation;
use App\Models\Currency;
use App\Models\Product;
use App\Services\UserRegistration;
use Illuminate\Foundation\Auth\RegistersUsers;
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


    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/group';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected UserRegistration $registration)
    {
        $this->middleware('guest');
		$this->redirectTo = route('group');
    }

    public function showRegistrationForm()
    {
		$accommodations = Accommodation::all();
		$products = Product::all();
		$def = Currency::def();
		return view ('auth.register', compact('accommodations', 'products', 'def'))->with('login', true);
	}

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'passport' => ['required', 'string', 'max:255'],
            'gender' => ['required'],
            'residence' => ['required', 'string', 'max:255'],
            'organisation' => ['required', 'string', 'max:255'],
            'orgtype' => ['required', 'string', 'max:255'],
            'orgtypeother' => $data['orgtype'] == 'other'? ['required', 'string', 'max:255'] : '',
            'address' => ['required', 'string', 'max:255'],
            'town' => ['required', 'string', 'max:255'],
            'zipcode' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:255'],
            'accommodation' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return \App\Models\User
     * @throws \Exception
     */
    protected function create(array $data)
    {
		$accommodation = Accommodation::find($data['accommodation']);
        $user = $this->registration->registerUser($data, 'groupadmin');
        return $user;
    }
}
