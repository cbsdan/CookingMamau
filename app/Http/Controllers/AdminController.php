<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\BakedGood;
use App\Models\OrderedGood;
use Illuminate\Http\Request;
use App\Models\AvailableSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index() {
        $users = User::all();
        $orders = Order::all();

        //Chart
        $schedules = AvailableSchedule::where('schedule', '<', Carbon::now())
        ->orderBy('schedule', 'desc')
        ->take(7)
        ->with('orders')
        ->get();
        $schedules = $schedules->sortBy('schedule');
        $revenueData = [];

        foreach ($schedules as $schedule) {
            $scheduleRevenue = 0;

            foreach ($schedule->orders as $order) {
                $scheduleRevenue += $order->payment->amount;
            }
            $carbonDate = Carbon::parse($schedule->schedule);
            $schedule = $carbonDate->format('Y-m-d');

            $revenueData[] = [
                'schedule' => $schedule, 
                'revenue' => $scheduleRevenue,
            ];
        }

        // Retrieve the IDs of successfully delivered orders
        $deliveredOrderIds = OrderedGood::whereHas('order', function ($query) {
            $query->where('order_status', 'Delivered');
        })->pluck('id_baked_goods')->toArray();
        $productCounts = OrderedGood::whereIn('id_baked_goods', $deliveredOrderIds)
            ->select('id_baked_goods', DB::raw('sum(qty) as count'))
            ->groupBy('id_baked_goods')
            ->orderByDesc('count')
            ->take(10) 
            ->get();
        $topProducts = [];
        foreach ($productCounts as $productCount) {
            $product = BakedGood::find($productCount->id_baked_goods); 

            if ($product) {
                $topProducts[] = [
                    'name' => $product->name,
                    'qty' => $productCount->count,
                ];
            }
        }
        // Prepare data for the chart
        $chartData = [
            'labels' => collect($topProducts)->pluck('name'),
            'data' => collect($topProducts)->pluck('qty'),
        ];

        return view('admin.dashboard', compact('users', 'orders', 'revenueData', 'chartData'));
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
