<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Discount;
use App\Models\OrderedGood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validation rules for the order form
        $validator = Validator::make($request->all(), [
            'buyer_name' => 'required|string|max:255',
            'email_address' => 'required|email|max:255',
            'delivery_address' => 'required|string|max:255',
            'buyer_note' => 'nullable|string',
            'discount_code' => 'nullable|string|max:255',
            'id_schedule' => 'required|exists:available_schedules,id',
            'mode' => 'required|in:GCash,COD',
            'amount' => 'required|numeric|min:0',
            'bakedGoods.*' => 'required|exists:baked_goods,id',
            'bakedGoodPrices.*' => 'required|numeric|min:0',
            'bakedGoodQtys.*' => 'required|integer|min:1',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create the order
        $order = new Order();

        $discountCode = $request->input('discount_code');
        $discount = Discount::where('discount_code', $discountCode)->first();

        // If the discount code exists, continue with inserting the order
        if ($discount) {
            $order->discount_code = $discount->discount_code;
        }

        $order->buyer_name = $request->input('buyer_name');
        $order->email_address = $request->input('email_address');
        $order->delivery_address = $request->input('delivery_address');
        $order->buyer_note = $request->input('buyer_note');
        
        $order->shipping_cost = 50;
        $order->id_schedule = $request->input('id_schedule');
        $order->order_status = 'Pending';
        $order->id_buyer = auth()->user()->buyer->id; // Assuming authenticated user

        // Save the order
        $order->save();

        
        // Create payment
        $payment = new Payment();
        $payment->mode = $request->input('mode');
        $payment->amount = $request->input('amount');
        $payment->id_buyer = auth()->user()->buyer->id; // Assuming authenticated user
        $payment->id_order = $order->id;
        $payment->save();

        // Create order items
        foreach ($request->input('bakedGoods') as $index => $bakedGoodId) {
            $orderedGood = new OrderedGood();
            $orderedGood->id_order = $order->id; // Assign the order ID
            $orderedGood->id_baked_goods = $bakedGoodId;
            $orderedGood->price_per_good = $request->input('bakedGoodPrices')[$index];
            $orderedGood->qty = $request->input('bakedGoodQtys')[$index];
            $orderedGood->save();
        }
        
        session()->forget('cart');

        // Return success response
        return redirect()->route('user.orders')->with('success', 'Ordered successfully.');

    }
    public function show(Order $order) {
        return view('order.show', compact('order'));
    }
    public function userOrders()
    {
        // Retrieve the authenticated user's ID
        if (auth()->check() && auth()->user()->is_admin) {
            // Retrieve orders and related information for the user
            $userOrders = Order::with('orderedGoods', 'payment', 'discount')
                                ->orderBy('created_at', 'desc')
                                ->get();
        } else {
            $userId = auth()->user()->buyer->id;
            // Retrieve orders and related information for the user
            $userOrders = Order::with('orderedGoods', 'payment', 'discount')
                                ->where('id_buyer', $userId)
                                ->orderBy('created_at', 'desc')
                                ->get();

        }


        return view('order.index', compact('userOrders'));
    }
    public function updateStatus(Request $request, Order $order)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'order_status' => 'required|in:Pending,Canceled,Preparing,Out for Delivery,Delivered',
        ]);
    
        // Update the order status
        $order->update([
            'order_status' => $validatedData['order_status'],
        ]);
        
        if ($order->order_status == "Delivered") {
            return redirect()->route('email.sent.receipt', $order->id);
        }
        // Redirect back with success message
        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
    
}
