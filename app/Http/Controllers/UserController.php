<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('buyer')
            ->where('email', '!=', 'cookingmamau@gmail.com')
            ->get();
        return response()->json(['users' => $users]);
    }

    public function store(Request $request)
    {
        try {
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

            if (isset($userData['image'])) {
                $file = $userData['image'];
                $extension = $file->getClientOriginalExtension();
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

            // Return success response
            return response()->json([
                'message' => 'User created successfully.',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'message' => 'Error creating user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(User $user)
    {
        return response()->json([
            'user' => $user->load('buyer')
        ], 200);
    }

    public function update(Request $request, User $user)
    {
        try {
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
                'password' => isset($userData['password']) ? Hash::make($userData['password']) : $user->password,
                'is_admin' => $userData['is_admin'],
                'is_activated' => $userData['is_activated'],
            ]);

            // Handle profile image if provided
            if (isset($userData['image'])) {
                $file = $userData['image'];
                $extension = $file->getClientOriginalExtension();
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

            // Return success response
            return response()->json([
                'message' => 'User updated successfully.',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'message' => 'Error updating user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy(User $user)
    {
        try {
            // Delete the user
            $user->delete();

            // Return success response
            return response()->json([
                'message' => 'User deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'message' => 'Error deleting user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function register()
    {
        return view('auth.register');
    }
    public function registerUser(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|unique:users',
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'contact' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'barangay' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'landmark' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'password' => 'required|min:8',
                'cpassword' => 'required|min:8|same:password',
            ],
            [
                'cpassword.same' => 'Password did not match!',
                'cpassword.required' => 'Confirm password is required!',
            ],
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors(),
            ]);
        } else {
            DB::beginTransaction();
            try {
                $profileImagePath = null;
                if ($request->hasFile('image')) {
                    $profileImagePath = $request->file('image')->store('uploaded_files', 'public');
                }
                $user = $this->createUser($request, $profileImagePath);
                $buyer = $this->createBuyer($request, $user->id);
                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'User successfully registered!',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error creating user: ' . $e->getMessage());
                return response()->json([
                    'status' => 500,
                    'message' => 'Internal Server Error',
                ]);
            }
        }
    }
    private function createUser($request, $profileImagePath)
    {
        return User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_image_path' => $profileImagePath,
            'is_admin' => 0,
            'is_activated' => 1,
        ]);
    }
    private function createBuyer($request, $userId)
    {
        return Buyer::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'contact' => $request->contact,
            'address' => $request->address,
            'barangay' => $request->barangay,
            'city' => $request->city,
            'landmark' => $request->landmark,
            'id_user' => $userId,
        ]);
    }
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function profile(Request $request)
    {
        // Determine the appropriate response based on user's is_admin status
        if ($request->user()->is_admin == 1) {
            return response()->json([
                'redirect' => route('admin.dashboard')
            ], 200);
        } else {
            return response()->json([
                'profile' => $request->user()
            ], 200);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function getUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['user' => $user]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->is_admin = $request->input('role') == 'admin';
        $user->is_activated = $request->input('active_status') == 'active';
        $user->save();

        return response()->json(['user' => $user, 'message' => 'User updated successfully']);
    }

    // Update user role
    public function updateRole(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->is_admin = $request->input('is_admin');
            $user->save();

            return response()->json([
                'message' => 'Role updated successfully.',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating role.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update user status
    public function updateStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->is_activated = $request->input('is_activated');
            $user->save();

            return response()->json([
                'message' => 'Status updated successfully.',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
