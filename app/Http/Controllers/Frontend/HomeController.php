<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::whereNull('parent_id')
            ->where('status', 'ACTIVE')
            ->with('children')
            ->get();

        $productsQuery = Product::with('images', 'category')
            ->where('is_active', true);

        if ($request->filled('category') && $request->category !== 'all') {
            $selectedCategory = Category::find($request->category);

            if ($selectedCategory) {
                $categoryIds = $selectedCategory->children()->pluck('id')->push($selectedCategory->id);
                $productsQuery->whereIn('category_id', $categoryIds);
            }
        }

        $featuredProducts = $productsQuery->latest()->take(10)->get();

        $countries = Country::orderBy('name')->get();

        return view('frontend.home', compact('categories', 'featuredProducts', 'countries'));
    }
    public function getStates($country_id)
    {
        $states = \App\Models\State::where('country_id', $country_id)->orderBy('name')->get();
        return response()->json($states);
    }

    public function getCities($state_id)
    {
        $cities = \App\Models\City::where('state_id', $state_id)->orderBy('name')->get();
        return response()->json($cities);
    }
}
