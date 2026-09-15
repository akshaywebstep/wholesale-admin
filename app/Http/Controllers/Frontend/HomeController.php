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
            ->orderBy('name', 'asc')
            ->with(['children' => function ($q) {
                $q->where('status', 'ACTIVE')->orderBy('name', 'asc');
            }])
            ->get();

        $categorySections = $categories->map(function ($cat) {
            $childIds = $cat->children->pluck('id')->push($cat->id);
            $products = Product::whereIn('category_id', $childIds)
                ->where('is_active', true)
                ->with(['images', 'priceTiers', 'unit', 'category'])
                ->latest()
                ->get();

            return (object)[
                'category' => $cat,
                'products' => $products,
                'total_count' => $products->count(),
            ];
        })->filter(function ($item) {
            return $item->products->isNotEmpty();
        })->sortBy('category.name', SORT_NATURAL | SORT_FLAG_CASE)->values();

        $countries = Country::orderBy('name')->get();

        return view('frontend.home', compact('categories', 'categorySections', 'countries'));
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
