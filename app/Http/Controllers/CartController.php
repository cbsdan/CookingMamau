<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\AvailableSchedule;
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

    public function destroy(Request $request)
    {
        // Retrieve the user ID from the request
        $userId = $request->input('id_user');

        // Check if the user ID is provided
        if (!$userId) {
            return response()->json(['message' => 'User ID is required.'], 400);
        }

        // Delete all cart items for the given user ID
        $deletedCount = CartItem::where('id_user', $userId)->delete();

        // Return a response indicating the number of items deleted
        return response()->json([
            'message' => 'All cart items removed successfully.',
            'deleted_count' => $deletedCount
        ]);
    }

    public function updateCart(Request $request)
    {
        $idCart = $request->input('id');
        $quantity = $request->input('qty');

        // Find the cart item for this user
        $cartItem = CartItem::where('id', $idCart)
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
        $userId = $request->id_user;
        $cartItems = CartItem::with('bakedGood.images')->where('id_user', $userId)->get();
        $user = User::with('buyer')->find($userId);
        $availableSchedules = AvailableSchedule::where('schedule', '>=', Carbon::now())->limit(20)->get();

        return response()->json([
            'cartItems' => $cartItems,
            'user' => $user,
            'availableSchedules' => $availableSchedules,
        ], 200);
    }
}
