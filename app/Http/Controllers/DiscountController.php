<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use App\Imports\DiscountImport;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::orderBy('created_at', 'DESC')->get();
        return response()->json($discounts);
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

        $discount = Discount::create($request->all());

        return response()->json(['success' => 'Discount created successfully.', 'discount' => $discount, 'status' => 200]);
    }

    public function show($id){
        $discount = Discount::find($id);
        return response()->json($discount);
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

        return response()->json(['success' => 'Discount updated successfully.', 'discount' => $discount, 'status' => 200]);
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();

        return response()->json(['success' => 'Discount deleted successfully.', 'status' => 200]);
    }

    public function checkDiscount(Request $request)
    {
        $discountCode = $request->input('discountCode');

        // Check if the discount code exists
        $discount = Discount::where('discount_code', $discountCode)->first();

        // Return JSON response with discount details
        return response()->json($discount);
    }

    public function discountImport(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'item_upload' => 'required|file|mimes:xlsx,xls,csv', // Ensure the file type is correct
        ]);

        try {
            // Import the file
            Excel::import(new DiscountImport, $request->file('item_upload'));

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'File imported successfully',
            ], 200);
        } catch (\Exception $e) {
            // Return an error response if the import fails
            return response()->json([
                'success' => false,
                'message' => 'Error importing file: ' . $e->getMessage(),
            ], 500);
        }
    }
}
