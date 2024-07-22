<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BakedGood;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // Retrieve the search query from the request
        $query = $request->input('q');

        // Perform the search on the BakedGood model to get the IDs of matching records
        $ids = BakedGood::search($query)->get()->pluck('id');

        // Retrieve the BakedGood records with eager loading for the images
        $results = BakedGood::with('images')->whereIn('id', $ids)->get();

        // Return a JSON response with the search results
        return response()->json([
            'results' => $results
        ]);
    }
}
