<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\User; // Import User model

class DashboardController extends Controller
{
    public function dashboard()
    {
        $users = User::with('buyer')->where('is_admin', "!=", "1")->get(); // Fetch all users
        $userCount = User::count(); // Count all users

        // If the request is AJAX, return JSON response
        if (request()->ajax()) {
            return response()->json([
                'users' => $users,
                'userCount' => $userCount
            ]);
        }

        // For regular HTTP requests, return the view
        return view('admin.dashboard', compact('users', 'userCount'));
    }

    public function adminAction(Request $request)
    {
        // Implement your admin actions here
        return response()->json(['message' => 'Admin action executed successfully.']);
    }

    public function showOrderSummary()
    {
        $orderSummary = Order::getOrderSummary();
        return view('admin.dashboard', compact('orderSummary'));
    }

}
