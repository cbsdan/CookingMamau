<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function viewCart()
    {
        // Retrieve the current cart items from the session
        $cartItems = session()->get('cart', []);
    
        // Pass the cart items to the view for rendering
        return view('cart.view', compact('cartItems'));
    }

    public function checkout()
    {
        // Add logic to handle the checkout process
    }
}
