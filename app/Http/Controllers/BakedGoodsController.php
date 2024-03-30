<?php

namespace App\Http\Controllers;

use App\Models\BakedGood;
use Illuminate\Http\Request;
use App\Models\BakedGoodImage;

class BakedGoodsController extends Controller
{
    /**
     * Display a listing of the baked goods.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Retrieve the search query from the request
        $searchQuery = $request->input('search');
    
        // Query baked goods based on search query
        $bakedGoods = BakedGood::query()
            ->where('id', 'like', '%' . $searchQuery . '%')
            ->orWhere('name', 'like', '%' . $searchQuery . '%')
            ->get();
    
        // Return the view with the filtered baked goods
        return view('baked_goods.index', compact('bakedGoods'));
    }

    /**
     * Show the form for creating a new baked good.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('baked_goods.create');
    }

    /**
     * Store a newly created baked good in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'is_available' => 'required|boolean',
            'description' => 'nullable|string',
            'weight_gram' => 'nullable|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for images
        ]);
    
        // Create baked good
        $bakedGood = BakedGood::create($request->except('images'));
    
        // Upload and attach images
        if ($request->hasFile('images')) {
            // Upload and attach images
            foreach ($request->file('images') as $index => $image) {
                // Check if the image upload is successful
                if ($image->isValid()) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    $path = 'uploaded_files/';
                    $uploadSuccess = $image->move($path, $filename);
        
                    // If the image upload is successful, create a BakedGoodImage record
                    if ($uploadSuccess) {
                        $bakedGoodImage = BakedGoodImage::create([
                            'image_path' => $path . $filename,
                            'id_baked_goods' => $bakedGood->id,
                        ]);
        
                        // Set the thumbnail image ID only once, typically for the first image
                        if ($index === 0) {
                            $bakedGood->thumbnail_image_id = $bakedGoodImage->id;
                        }
                    }
                }
            }
        
            // Save the baked good after all images are processed
            $bakedGood->save();
        }
    
        return redirect()->route('baked_goods.index')
            ->with('success', 'Baked good created successfully.');
    }
    

    /**
     * Display the specified baked good.
     *
     * @param  \App\Models\BakedGood  $bakedGood
     * @return \Illuminate\Http\Response
     */
    public function show(BakedGood $bakedGood)
    {
        return view('baked_goods.show', compact('bakedGood'));
    }

    /**
     * Show the form for editing the specified baked good.
     *
     * @param  \App\Models\BakedGood  $bakedGood
     * @return \Illuminate\Http\Response
     */
    public function edit(BakedGood $bakedGood)
    {
        return view('baked_goods.edit', compact('bakedGood'));
    }

    /**
     * Update the specified baked good in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BakedGood  $bakedGood
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BakedGood $bakedGood)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'is_available' => 'required|boolean',
            'description' => 'nullable|string',
            'weight_gram' => 'nullable|integer',
            'thumbnail_image_id' => 'nullable|exists:baked_good_images,id,id_baked_goods,' . $bakedGood->id,
            // Add validation for other fields if needed
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for images
        ]);
    
        // Update baked good
        $bakedGood->update($request->except('images'));
        $isNoThumbnail = $bakedGood->thumbnail_image_id ? true : false;
        // Upload and attach images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $extension = $image->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;
                $path = 'uploaded_files/';
                $uploadSuccess = $image->move($path, $filename);
                 // If the image upload is successful, create a BakedGoodImage record
                 if ($uploadSuccess) {
                    $bakedGoodImage = BakedGoodImage::create([
                        'image_path' => $path . $filename,
                        'id_baked_goods' => $bakedGood->id,
                    ]);
    
                    // Set the thumbnail image ID only once, typically for the first image
                    if ($index === 0 && $isNoThumbnail) {
                        $bakedGood->thumbnail_image_id = $bakedGoodImage->id;
                    }
                }
            }
        }
    
        return redirect()->route('baked_goods.index')
            ->with('success', 'Baked good updated successfully.');
    }

    /**
     * Remove the specified baked good from storage.
     *
     * @param  \App\Models\BakedGood  $bakedGood
     * @return \Illuminate\Http\Response
     */
    public function destroy(BakedGood $bakedGood)
    {
        $bakedGood->delete();
        return redirect()->route('baked_goods.index')
            ->with('success', 'Baked good deleted successfully.');
    }

    public function deleteImage(BakedGoodImage $bakedGoodImage)
    {
        // Delete the specific image
        $bakedGoodImage->delete();
    
        // Redirect back to the baked good edit page with a success message
        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
}
