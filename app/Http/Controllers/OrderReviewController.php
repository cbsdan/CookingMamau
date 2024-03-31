<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderReview;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderReviewController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->is_admin) {
            $orderReviews = OrderReview::with('reviewImages')->get();
        } else {
            $buyerId = auth()->user()->buyer->id;
            $orderReviews = OrderReview::with('reviewImages')
                ->whereHas('order', function ($query) use ($buyerId) {
                    $query->where('id_buyer', $buyerId);
                })
                ->get();
        }
        
        return view('order_reviews.index', compact('orderReviews'));
    }

    public function create(Order $order)
    {
        return view('order_reviews.create', compact('order'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required|string|max:255',
            'id_order' => 'required|exists:orders,id', // Validation rule for id_order
            'review_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validation rules for review images
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Assuming you have 'id_order' field in your form
        $orderReview = OrderReview::create([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'id_order' => $request->id_order,
        ]);
    
        // If there are review images attached, handle them here
        if ($request->hasFile('review_images')) {
            foreach ($request->file('review_images') as $image) {
                // Generate a unique filename based on the current timestamp
                $filename = time() . '.' . $image->getClientOriginalExtension();
                
                // Define the path where the file will be stored
                $path = 'uploaded_files/';
                
                // Move the uploaded file to the defined path with the generated filename
                $image->move($path, $filename);
                
                // Save the image path in the database
                $reviewImage = new ReviewImage(['image_path' => $path . $filename]);
                $orderReview->reviewImages()->save($reviewImage);
            }
        }
        
    
        return redirect()->route('order_reviews.index')->with('success', 'Order review created successfully.');
    }
    
    

    public function show(OrderReview $orderReview)
    {
        return view('order_reviews.show', compact('orderReview'));
    }

    public function edit(OrderReview $orderReview)
    {
        return view('order_reviews.edit', compact('orderReview'));
    }

    public function update(Request $request, OrderReview $orderReview)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required|string|max:255',
            'review_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validation rules for review images
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Update the existing order review data
        $orderReview->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            // Add other fields you may want to update
        ]);
    
        if ($request->hasFile('review_images')) {
            foreach ($request->file('review_images') as $image) {
                // Generate a unique filename based on the current timestamp
                $filename = time() . '.' . $image->getClientOriginalExtension();
                
                // Define the path where the file will be stored
                $path = 'uploaded_files/';
                
                // Move the uploaded file to the defined path with the generated filename
                $image->move($path, $filename);
                
                // Save the image path in the database
                $reviewImage = new ReviewImage(['image_path' => $path . $filename]);
                $orderReview->reviewImages()->save($reviewImage);
            }
        }
        
    
        return redirect()->route('order_reviews.show', $orderReview->id)->with('success', 'Order review updated successfully.');
    }    

    public function destroy(OrderReview $orderReview)
    {
        $orderReview->delete();
        return redirect()->route('order_reviews.index')->with('success', 'Order review deleted successfully.');
    }
    
    public function destroyReviewImage(ReviewImage $image)
    {
        // Delete the review image
        $image->delete();

        return redirect()->back()->with('success', 'Review image deleted successfully.');
    }
}

