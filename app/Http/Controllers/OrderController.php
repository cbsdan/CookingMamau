<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Discount;
use App\Models\OrderedGood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve the order_status filter from the request
        $orderStatus = $request->input('order_status');

        // Build the query
        $query = Order::with('orderedGoods', 'payments', 'discount', 'schedule')->orderBy('created_at', 'desc');

        // Apply the filter if provided
        if ($orderStatus) {
            $query->where('order_status', $orderStatus);
        }

        // Execute the query and get the results
        $orders = $query->get();

        // Return the results as JSON
        return response()->json($orders, 200);
    }

    public function userOrder(Request $request) {
        $idBuyer = $request->route('id'); // Get the ID from the route
        $orders = Order::with('orderedGoods', 'payments', 'discount', 'schedule')
                        ->orderBy('created_at', 'desc')
                        ->where('id_buyer', $idBuyer) // Filter by the buyer ID
                        ->get();
        return response()->json($orders, 200);
    }

    public function store(Request $request)
    {
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

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'request' => $request->all()], 422);
        }

        DB::beginTransaction(); // Start transaction

        try {
            $order = new Order();
            $discountCode = $request->discount_code;
            $discount = Discount::where('discount_code', $discountCode)->first();

            if ($discount) {
                $order->discount_code = $discount->discount_code;
            }

            $order->buyer_name = $request->buyer_name;
            $order->email_address = $request->email_address;
            $order->delivery_address = $request->delivery_address;
            $order->buyer_note = $request->buyer_note;
            $order->shipping_cost = 50;
            $order->id_schedule = $request->id_schedule;
            $order->order_status = 'Pending';
            $order->id_buyer = $request->id_buyer; // Consider using auth()->user()->buyer->id in production

            $order->save();

            $payment = new Payment();
            $payment->mode = $request->mode;
            $payment->amount = $request->amount;
            $payment->id_order = $order->id;
            $payment->save();

            foreach ($request->bakedGoods as $index => $bakedGoodId) {
                $orderedGood = new OrderedGood();
                $orderedGood->id_order = $order->id;
                $orderedGood->id_baked_goods = $bakedGoodId;
                $orderedGood->price_per_good = $request->bakedGoodPrices[$index];
                $orderedGood->qty = $request->bakedGoodQtys[$index];
                $orderedGood->save();
            }

            //Delete the cart items
            CartItem::where('id_user', $request->id_user)->delete();


            DB::commit(); // Commit transaction

            return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error

            return response()->json(['error' => 'Failed to create order', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        // Retrieve the order by ID
        $order = Order::with('orderedGoods', 'payments', 'discount', 'schedule')->findOrFail($id);

        // Return the order with related models in JSON format
        return response()->json(['order' => $order], 200);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully'], 200);
    }

    public function userOrders()
    {
        if (auth()->check() && auth()->user()->is_admin) {
            $userOrders = Order::with('orderedGoods', 'payment', 'discount')->orderBy('created_at', 'desc')->get();
        } else {
            $userId = auth()->user()->buyer->id;
            $userOrders = Order::with('orderedGoods', 'payment', 'discount')->where('id_buyer', $userId)->orderBy('created_at', 'desc')->get();
        }

        return response()->json($userOrders, 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $validatedData = $request->validate([
            'order_status' => 'required|in:Pending,Canceled,Preparing,Out for Delivery,Delivered',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['order_status' => $validatedData['order_status']]);

        return response()->json(['message' => 'Order status updated successfully'], 200);
    }
}
