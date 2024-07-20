<?php

namespace App\Http\Controllers;

use App\Models\BakedGood;
use Illuminate\Http\Request;
use App\Models\BakedGoodImage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BakedGoodsImport;

class BakedGoodsController extends Controller
{
    /**
     * Display a listing of the baked goods.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bakedGoods = BakedGood::with(['images', 'ingredients'])
                                ->orderBy('id', 'DESC')
                                ->get();
        return response()->json($bakedGoods);

    }
    public function bakedGoodPaginate()
    {
        $perPage = 10; // Number of items per page
        $bakedGoods = BakedGood::with(['images', 'ingredients'])
                                ->orderBy('id', 'DESC')
                                ->paginate($perPage);
        // If the request expects JSON, return paginated data
        return response()->json($bakedGoods);

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
            'ids_ingredients.*' => 'exists:ingredients,id', // Validation for ingredient IDs
            'qtys_ingredient.*' => 'numeric|min:1', // Validation for ingredient quantities

        ]);

        // Create baked good
        $bakedGood = BakedGood::create($request->except('images'));

        // Attach ingredients to the pivot table with quantities
        if ($request->has('ids_ingredient')) {
            $ingredients = [];
            foreach ($request->ids_ingredient as $index => $ingredientId) {
                $qty = $request->qtys_ingredient[$index] ?? 1; // Default quantity is 1 if not provided

                // Prepare data to attach with extra fields (like qty)
                $ingredients[$ingredientId] = ['qty' => $qty];
            }
            $bakedGood->ingredients()->attach($ingredients);
        }

        // Upload and attach images
        if ($request->hasFile('images')) {
            $bakedGoodImages = [];

            foreach ($request->file('images') as $index => $image) {
                // Check if the image upload is successful
                if ($image->isValid()) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    $path = 'uploaded_files/';
                    $uploadSuccess = $image->move($path, $filename);

                    // If the image upload is successful, create a BakedGoodImage record
                    if ($uploadSuccess) {
                        $bakedGoodImages[] = BakedGoodImage::create([
                            'image_path' => $path . $filename,
                            'id_baked_goods' => $bakedGood->id,
                        ]);

                    }
                }
            }
            $bakedGood->images()->saveMany($bakedGoodImages);


        }
        // Save the baked good after all images are processed
        $bakedGood->save();

        return response()->json(['success' => 'Baked Good created successfully.', 'bakedgood' => $bakedGood, 'ingredients'=> $request->ids_ingredient, 'status' => 200]);
    }


    /**
     * Display the specified baked good.
     *
     * @param  \App\Models\BakedGood  $bakedGood
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bakedGood = BakedGood::with(['images', 'ingredients'])->find($id);
        return response()->json($bakedGood);
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for images
            'ids_ingredients.*' => 'exists:ingredients,id', // Validation for ingredient IDs
            'qtys_ingredient.*' => 'numeric|min:1', // Validation for ingredient quantities

        ]);

        // Update baked good
        $bakedGood->update($request->except('images'));

        // Sync ingredients in the pivot table with quantities
        if ($request->has('ids_ingredient')) {
            $ingredients = [];
            foreach ($request->ids_ingredient as $index => $ingredientId) {
                $qty = $request->qtys_ingredient[$index] ?? 1; // Default quantity is 1 if not provided

                // Prepare data to sync with extra fields (like qty)
                $ingredients[$ingredientId] = ['qty' => $qty];
            }
            $bakedGood->ingredients()->sync($ingredients);
        }

        // Upload and attach images
        if ($request->hasFile('images')) {
            $bakedGoodImages = [];

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

                    // Add the image data to the array
                    $bakedGoodImages[] = $bakedGoodImage;
                }
            }

            // Convert the images array to JSON and add to the response
            $bakedGood->images = $bakedGoodImages;
        }

        return response()->json(['success' => 'Baked Good updated successfully.', 'bakedGood' => $bakedGood, 'ingredients'=> $request->ids_ingredient, 'status' => 200]);
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
        return response()->json(['success' => 'Baked Good deleted successfully.', 'status' => 200]);
    }

    public function deleteImage($id)
    {
        $image = BakedGoodImage::find($id);

        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Delete the image file from the server
        $imagePath = public_path($image->image_path);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete the image record from the database
        $image->delete();

        return response()->json(['success' => 'Image removed successfully']);
    }

    public function setThumbnail($imageId)
    {
        // Find the image by ID
        $image = BakedGoodImage::find($imageId);

        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Set all other images associated with the baked good to is_thumbnail = false
        $bakedGood = BakedGood::find($image->id_baked_goods);
        if ($bakedGood) {
            $bakedGood->images()->update(['is_thumbnail' => false]);
        }

        // Set the selected image as thumbnail
        $image->is_thumbnail = true;
        $image->save();

        return response()->json(['message' => 'Thumbnail updated successfully']);
    }

    public function bakedgoodsImport(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'item_upload' => 'required|file|mimes:xlsx,xls,csv', // Ensure the file type is correct
        ]);

        try {
            // Import the file
            Excel::import(new BakedGoodsImport, $request->file('item_upload'));

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
