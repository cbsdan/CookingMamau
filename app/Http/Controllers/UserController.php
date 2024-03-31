<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Show all users
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query();

        if ($search) {
            $users->where('id', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        }

        $users = $users->get();

        return view('admin.users.index', compact('users'));
    }

    // Show the form for creating a new user
    public function create()
    {
        return view('admin.users.create');
    }

    // Store a newly created user in storage
    public function store(Request $request)
    {
        // Validate the request data
        $userData = $request->validate([
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_admin' => ['required', 'boolean'],
            'is_activated' => ['required', 'boolean'],
        ]);

        // Create the user
        $user = User::create([
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'is_admin' => $userData['is_admin'],
            'is_activated' => $userData['is_activated'],
        ]);

        if (isset($userData['image'])){
            $file = $userData['image'];
            $extension = $file -> getClientOriginalExtension();
            $filename = time() . "." . $extension;
        
            $path = "uploaded_files/";
            $file->move($path, $filename);
            $user->profile_image_path = $path . $filename;
            $user->save();
        }

        // Create buyer information
        $user->buyer()->create([
            'fname' => $userData['fname'],
            'lname' => $userData['lname'],
            'contact' => $userData['contact'],
            'address' => $userData['address'],
            'barangay' => $userData['barangay'],
            'city' => $userData['city'],
            'landmark' => $userData['landmark'],
        ]);

        // Redirect after user creation
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }


    // Show the form for editing the specified user
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update the specified user in storage
    public function update(Request $request, User $user)
    {
        // Validate the request data
        $userData = $request->validate([
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'min:8'],
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_admin' => ['required', 'boolean'],
            'is_activated' => ['required', 'boolean'],
        ]);

        // Update the user
        $user->update([
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'profile_image_path' => $request->file('image') ? $request->file('image')->store('uploaded_files') : $user->profile_image_path,
            'is_admin' => $userData['is_admin'],
            'is_activated' => $userData['is_activated'],
        ]);
        
        if (isset($userData['image'])){
            $file = $userData['image'];
            $extension = $file -> getClientOriginalExtension();
            $filename = time() . "." . $extension;
        
            $path = "uploaded_files/";
            $file->move($path, $filename);
            $user->profile_image_path = $path . $filename;
            $user->save();
        }

        // Update buyer information if available
        if ($user->buyer) {
            $user->buyer->update([
                'fname' => $userData['fname'],
                'lname' => $userData['lname'],
                'contact' => $userData['contact'],
                'address' => $userData['address'],
                'barangay' => $userData['barangay'],
                'city' => $userData['city'],
                'landmark' => $userData['landmark'],
            ]);
        }

        // Redirect after updating user
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    // Remove the specified user from storage
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
    public function deactivate(User $user)
    {
        $user->update(['is_activated' => false]);
        return redirect()->route('admin.users.index')->with('success', 'User deactivated successfully.');
    }
}
