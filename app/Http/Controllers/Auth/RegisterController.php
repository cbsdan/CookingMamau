<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Buyer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (isset($data['image'])){
            $file = $data['image'];
            $extension = $file -> getClientOriginalExtension();
            $filename = time() . "." . $extension;
        
            $path = "uploaded_files/";
            $file->move($path, $filename);
            $user->profile_image_path = $path . $filename;
            $user->save();
        }
        
        $buyer = Buyer::create([
            'fname' => $data['fname'],
            'lname' => $data['lname'],
            'contact' => $data['contact'],
            'address' => $data['address'],
            'barangay' => $data['barangay'],
            'city' => $data['city'],
            'landmark' => $data['landmark'],
            'id_user' => $user->id,
        ]);

        // Authenticate the user after registration
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Account registered successfully.');
    }
}