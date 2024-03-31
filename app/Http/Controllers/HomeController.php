<?php

namespace App\Http\Controllers;

use App\Models\BakedGood;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        
        // Perform search if query is provided
        if ($query) {
            $bakedGoods = BakedGood::where('name', 'like', "%$query%")->get();
        } else {
            // Fetch all baked goods if no query is provided
            $bakedGoods = BakedGood::all();
        }

        return view('welcome', compact('bakedGoods'));
    }
    public function home(Request $request)
    {
        // Check if user is authenticated
        if ($request->user()) {
            $bakedGoods = BakedGood::all();
            return view('home', compact('bakedGoods'));
        } else {
            return redirect()->route('login')->with('error', 'You must be authenticated to access this page.'); // Redirect to login page
        }
    }

}
