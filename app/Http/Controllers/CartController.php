<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\AvailableSchedule;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $bakedGoodId = $request->input('id_baked_good');
        $quantity = $request->input('qty');
        $userId = $request->input('id_user');

        $isUpdated = false;;
        // Check if the item already exists in the cart for this user
        $cartItem = CartItem::where('id_user', $userId)
                             ->where('id_baked_good', $bakedGoodId)
                             ->first();

        if ($cartItem) {
            // If it exists, update the quantity
            $cartItem->qty += $quantity;
            $cartItem->save();
            $isUpdated = true;
        } else {
            // If it doesn't exist, create a new cart item
            CartItem::create([
                'id_user' => $userId,
                'id_baked_good' => $bakedGoodId,
                'qty' => $quantity,
            ]);
        }

        return response()->json(['message' => 'Item added to cart successfully.', 'isUpdated' => $isUpdated]);
    }

    public function removeFromCart(Request $request)
    {
        $cartId = $request->input('id'); // Retrieve the 'id' from the request
        $cartItem = CartItem::find($cartId);

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['message' => 'Product removed from cart.']);
        }

        return response()->json(['message' => 'Product not found in cart.'], 404);
    }

    public function updateCart(Request $request)
    {
        $bakedGoodId = $request->input('id_baked_good');
        $quantity = $request->input('qty');
        $userId = $request->input('id_user');

        // Find the cart item for this user
        $cartItem = CartItem::where('id_user', $userId)
                             ->where('id_baked_good', $bakedGoodId)
                             ->first();

        if ($cartItem) {
            // Update the quantity
            $cartItem->qty = $quantity;
            $cartItem->save();
            return response()->json(['message' => 'Cart updated successfully.']);
        }

        return response()->json(['message' => 'Product not found in cart.'], 404);
    }

    public function fetchCartItems(Request $request)
    {
        $userId = $request->id_user;
        $cartItems = CartItem::where('id_user', $userId)
            ->with('bakedGood.images')
            ->orderBy('id', 'desc') // Order by latest id first
            ->get();
        $grandTotal = $cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->qty * $item->bakedGood->price);
        }, 0);

        return response()->json([
            'cartItems' => $cartItems,
            'grandTotal' => $grandTotal
        ]);
    }


    public function checkout(Request $request)
    {
        $userId = $request->input('id_user');
        $cartItems = CartItem::where('id_user', $userId)->get();
        $user = Auth::user();
        $availableSchedules = AvailableSchedule::where('schedule', '>=', Carbon::now())->limit(20)->get();
        return view('order.checkout', compact('cartItems', 'user', 'availableSchedules'));
    }
}
