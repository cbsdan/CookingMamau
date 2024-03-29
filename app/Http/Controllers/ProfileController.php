<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class ProfileController extends Controller
{
    public function index()
    {
        return view('auth.profile');
    }
    
    public function update(Request $request)
    {
        try {
            $user = User::find(auth()->id());
            $data = $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
                'fname' => ['required', 'string', 'max:255'],
                'lname' => ['required', 'string', 'max:255'],
                'contact' => ['required', 'string', 'max:255'],
                'address' => ['nullable', 'string', 'max:255'],
                'barangay' => ['nullable', 'string', 'max:255'],
                'city' => ['nullable', 'string', 'max:255'],
                'landmark' => ['nullable', 'string', 'max:255'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
            ]);

            // Update user's email and password if provided
            if (isset($data['email'])) {
                $user->email = $data['email'];
            }
            if (isset($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            // Update buyer information
            $buyer = $user->buyer;
            if ($buyer) {
                $buyer->update([
                    'fname' => $data['fname'],
                    'lname' => $data['lname'],
                    'contact' => $data['contact'],
                    'address' => $data['address'],
                    'barangay' => $data['barangay'],
                    'city' => $data['city'],
                    'landmark' => $data['landmark'],
                ]);
            }

            // Handle profile image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . "." . $extension;
                $path = "uploaded_files/";
                $file->move($path, $filename);
                $user->profile_image_path = $path . $filename;
            }

            $user->save();

            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function updatePassword(Request $request)
    {
        $user = User::find(auth()->id());
        
        $request->validate([
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    
        // Check if the old password matches the user's current password
        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors(['old_password' => 'The old password is incorrect.'])->withInput();
        }
    
        // Check if the new password matches the confirmed password
        if ($request->password !== $request->password_confirmation) {
            return redirect()->back()->withErrors(['password_confirmation' => 'The new password and confirmation do not match.'])->withInput();
        }
    
        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();
    
        return redirect()->back()->with('success', 'Password updated successfully.');
    }

}
