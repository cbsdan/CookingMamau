<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use Illuminate\Validation\Rule;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $discounts = Discount::query();

        // Search for discount code
        if ($request->has('search')) {
            $discounts->where('discount_code', 'like', '%' . $request->input('search') . '%');
        }

        $discounts = $discounts->paginate(10);

        return view('discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('discounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'discount_code' => ['required', 'string', 'max:255', 'unique:discounts'],
            'percent' => ['required', 'integer', 'min:1', 'max:100'],
            'max_number_buyer' => ['nullable', 'integer', 'min:1'],
            'min_order_price' => ['nullable', 'numeric', 'min:0'],
            'is_one_time_use' => ['required', 'boolean'],
            'discount_start' => ['required', 'date'],
            'discount_end' => ['required', 'date', 'after_or_equal:discount_start'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
        ]);
    
        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . "." . $extension;
            $path = "uploaded_files/";
            $file->move($path, $filename);
            $request->merge(['image_path' =>  $path . $filename]);
        }
    
        Discount::create($request->all());
    
        return redirect()->route('discounts.index')->with('success', 'Discount created successfully.');
    }

    public function edit(Discount $discount)
    {
        return view('discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'discount_code' => ['required', 'string', 'max:255', Rule::unique('discounts')->ignore($discount->discount_code, 'discount_code')],
            'percent' => ['required', 'integer', 'min:1', 'max:100'],
            'max_number_buyer' => ['nullable', 'integer', 'min:1'],
            'min_order_price' => ['nullable', 'numeric', 'min:0'],
            'is_one_time_use' => ['required', 'boolean'],
            'discount_start' => ['required', 'date'],
            'discount_end' => ['required', 'date', 'after_or_equal:discount_start'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
        ]);
    
        // Handle image update if provided
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . "." . $extension;
            $path = "uploaded_files/";
            $file->move($path, $filename);
            $request->merge(['image_path' =>  $path . $filename]);
        }
    
        $discount->update($request->all());
    
        return redirect()->route('discounts.index')->with('success', 'Discount updated successfully.');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();

        return redirect()->route('discounts.index')->with('success', 'Discount deleted successfully.');
    }

    public function checkDiscount(Request $request)
    {
        $discountCode = $request->input('discountCode');
        
        // Check if the discount code exists
        $discount = Discount::where('discount_code', $discountCode)->first();
        
        // Return JSON response with discount details
        return response()->json($discount);
    }
}
