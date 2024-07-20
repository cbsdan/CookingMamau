<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User; // Import User model
class DashboardController extends Controller
{
    public function dashboard()
    {
        $users = User::all(); // Fetch all users

        // If the request is AJAX, return JSON response
        if (request()->ajax()) {
            return response()->json(['users' => $users]);
        }

        // For regular HTTP requests, return the view
        return view('admin.dashboard', compact('users'));
    }

    public function adminAction(Request $request)
    {
        // Implement your admin actions here
        return response()->json(['message' => 'Admin action executed successfully.']);
    }
}
