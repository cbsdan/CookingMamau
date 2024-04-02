<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index() {
        return view('admin.dashboard');
    }
    public function profile() {
        return view('admin.profile');
    }
    
    public function updateProfile(Request $request)
    {   
        $user = User::find(auth()->id());
        
        // Validate the request data
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Validation rules for image
        ]);
    
        // Check if the new email is unique
        if ($user->email !== $request->email) {
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                return redirect()->back()->withErrors(['email' => 'The email has already been taken.'])->withInput();
            }
        }
    
        // Update the user's email and profile_image_path if an image is uploaded
        $user->email = $request->email;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = 'uploaded_files/';
            $file->move(public_path($path), $filename);
            $user->profile_image_path = $path . $filename;
        }
        $user->save();
    
        // Redirect back with success message
        return redirect()->back()->with('success', 'Email and profile image updated successfully.');
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
