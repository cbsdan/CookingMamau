<?php

namespace App\Http\Controllers;

use App\Models\BakedGood;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Imports\IngredientsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class IngredientController extends Controller
{
    /**
     * Display a listing of the ingredients.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() //Request $request
    {
        $ingredients = Ingredient::orderBy('id', 'DESC')->get();

        return response()->json($ingredients);

        // $query = Ingredient::query();

        // // Check if search query is provided
        // if ($request->has('search')) {
        //     $searchTerm = $request->input('search');
        //     // Search by name of baked goods
        //     $query->whereHas('bakedGood', function ($query) use ($searchTerm) {
        //         $query->where('name', 'like', '%' . $searchTerm . '%');
        //     });
        // }

        // // Include the baked goods related to the ingredients
        // $ingredients = $query->with('bakedGood')->get();

        // return view('ingredients.index', compact('ingredients'));
    }

    /**
     * Show the form for creating a new ingredient.
     *
     * @return \Illuminate\Http\Response
     */
    // public function add(BakedGood $bakedGood)
    // {
    //     return view('ingredients.add', compact('bakedGood'));
    // }
    // public function create()
    // {
    //     $bakedGoods = BakedGood::all(); // Fetch all baked goods
    //     return view('ingredients.create', compact('bakedGoods'));
    // }

    /**
     * Store a newly created ingredient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for image file
        ]);

        // Store the image file
        $imagePath = null;

        // Handle profile image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . "." . $extension;
            $path = "uploaded_files/";
            $file->move($path, $filename);
            $imagePath = $path . $filename;
        }

        // Create the ingredient
        $ingredient = new Ingredient([
            'name' => $request->name,
            'unit' => $request->unit,
            'image_path' => $imagePath,
        ]);
        $ingredient->save();

        return response()->json(["success" => "Ingredient created successfully.", "ingredient" => $ingredient, "status" => 200]);

        // return redirect()->route('ingredients.index')
        //     ->with('success', 'Ingredient created successfully.');
    }

    /**
     * Show the form for editing the specified ingredient.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    // public function edit(Ingredient $ingredient)
    // {
    //     $bakedGoods = BakedGood::all(); // Fetch all baked goods
    //     return view('ingredients.edit', compact('ingredient', 'bakedGoods'));
    // }

    public function show($id){
        $ingredient = Ingredient::with('bakedGoods')->find($id);
        return response()->json($ingredient);
    }

    /**
     * Update the specified ingredient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for image file
        ]);

        // Update the ingredient attributes
        $ingredient->name = $request->name;
        $ingredient->unit = $request->unit;

        // Store the image file
        $imagePath = null;

        // Handle profile image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . "." . $extension;
            $path = "uploaded_files/";
            $file->move($path, $filename);
            $imagePath = $path . $filename;
            $ingredient->image_path = $imagePath;
        }

        $ingredient->save();

        return response()->json(["success" => "Ingredient updated successfully.", "ingredient" => $ingredient, "status" => 200]);
    }

    /**
     * Remove the specified ingredient from storage.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        $data = array('success' => 'deleted','code'=>200);
        return response()->json($data);
    }

    public function ingredientImport(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'item_upload' => 'required|file|mimes:xlsx,xls,csv', // Ensure the file type is correct
        ]);

        try {
            // Import the file
            Excel::import(new IngredientsImport, $request->file('item_upload'));

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
