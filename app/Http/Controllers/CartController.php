<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\AvailableSchedule;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $bakedGoodId = $request->input('id');
        $quantity = $request->input('qty');
        $name = $request->input('name');
        $image_path = $request->input('image_path');
        $price = $request->input('price');
    
        // Retrieve the cart data from session or initialize an empty array
        $cart = session()->get('cart', []);
        // Check if the item already exists in the cart
        if (array_key_exists($bakedGoodId, $cart)) {
            // If it exists, update the quantity
            $cart[$bakedGoodId]['quantity'] += $quantity;
        } else {
            // If it doesn't exist, add it to the cart
            $cart[$bakedGoodId] = [
                'name' => $name,
                'quantity' => $quantity,
                'price' => $price,
                'image_path' => $image_path
            ];
        }
    
        // Store the updated cart data back into session
        session()->put('cart', $cart);
    
        return back();
    }
    

    public function removeFromCart(Request $request)
    {
        // Retrieve the product ID to be removed from the request
        $bakedGoodId = $request->input('id');
    
        // Retrieve the current cart items from the session
        $cartItems = session()->get('cart', []);
    
        // Check if the product exists in the cart
        if (isset($cartItems[$bakedGoodId])) {
            // Remove the product from the cart
            unset($cartItems[$bakedGoodId]);
    
            // Save the updated cart items back to the session
            session()->put('cart', $cartItems);
    
            return redirect()->back()->with('success', 'Product removed from cart.');
        }
    
        return redirect()->back()->with('error', 'Product not found in cart.');
    }

    // public function viewCart()
    // {
    //     // Retrieve the current cart items from the session
    //     $cartItems = session()->get('cart', []);
    
    //     // Pass the cart items to the view for rendering
    //     return view('cart.view', compact('cartItems'));
    // }

    public function updateCart(Request $request)
    {
        // Retrieve the product ID and updated quantity from the request
        $bakedGoodId = $request->input('id');
        $quantity = $request->input('qty');

        // Retrieve the current cart items from the session
        $cartItems = session()->get('cart', []);

        // Check if the product exists in the cart
        if (isset($cartItems[$bakedGoodId])) {
            // Update the quantity of the specified product
            $cartItems[$bakedGoodId]['quantity'] = $quantity;

            // Save the updated cart items back to the session
            session()->put('cart', $cartItems);

            return redirect()->back()->with('success', 'Cart updated successfully.');
        }
        
        // If the product does not exist in the cart, redirect back with an error message
        return redirect()->back()->with('error', 'Product not found in cart.');
    }
    
    public function checkout()
    {
        $cartItems = session()->get('cart', []);
        $user = Auth::user();
        $availableSchedules = AvailableSchedule::where('schedule', '>=', Carbon::now())->limit(20)->get();
        return view('order.checkout', compact('cartItems', 'user', 'availableSchedules'));
    }
}
