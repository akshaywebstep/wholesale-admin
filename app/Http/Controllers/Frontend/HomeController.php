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

        $solutionCategories = Category::whereNull('parent_id')
            ->where('status', 'ACTIVE')
            ->with(['children'])
            ->get();

        $solutions = $solutionCategories->map(function ($cat) {
            $childIds = $cat->children->pluck('id')->push($cat->id);
            $product = Product::whereIn('category_id', $childIds)
                ->where('is_active', true)
                ->with(['images', 'priceTiers', 'unit'])
                ->first();

            $meta = match ($cat->slug) {
                'hookah-shisha' => [
                    'icon' => '🔥',
                    'badge' => 'High-Turnover Shisha SKUs',
                    'tagline' => 'Curated Hookah & Premium Shisha Molasses Packs',
                    'desc' => 'Direct factory pricing on world-renowned shisha flavors, natural coconut coals, durable foils, and master tobacco displays.',
                    'perks' => [
                        'Guaranteed authentic manufacturer batch codes',
                        'Breakage-free padded crate delivery',
                        'Seasonal high-demand flavor recommendations'
                    ]
                ],
                'vape-e-liquid' => [
                    'icon' => '⚡',
                    'badge' => 'Hot Trends & Disposables',
                    'tagline' => 'Trending Disposable Pods, E-Liquids & Hardware',
                    'desc' => 'Fast-moving disposable devices, multi-flavor pods, and starter vape gear with guaranteed authentic QR verification codes.',
                    'perks' => [
                        '100% authentic QR scratch code verified units',
                        'Priority access to weekly new flavor drops',
                        'Instant replacement policy on factory defects'
                    ]
                ],
                'smoke-shop-roll-your-own' => [
                    'icon' => '🌿',
                    'badge' => 'Impulse Counter Display',
                    'tagline' => 'High-Margin Rolling Papers, Cones & Wraps',
                    'desc' => 'Countertop displays and wholesale master boxes for rolling papers, natural hemp wraps, pre-rolled cones, and rolling accessories.',
                    'perks' => [
                        'Pre-assembled countertop acrylic display boxes',
                        'POS barcoded boxes for lightning-fast billing',
                        'Standing weekly replenishment on top-selling SKUs'
                    ]
                ],
                'snacks-confectionery' => [
                    'icon' => '🍿',
                    'badge' => 'Everyday Fast Impulse Sales',
                    'tagline' => 'Impulse Snack Cartons & Confectionery Packs',
                    'desc' => 'Master cartons of gourmet potato chips, energy bars, candy displays, and grab-and-go convenience snacks for retail counters.',
                    'perks' => [
                        'Fresh batch guarantee with extended expiry dates',
                        'Pre-priced retail hang tags & impulse strips',
                        'Multi-flavor assorted carton mixes'
                    ]
                ],
                'beverages-energy-drinks' => [
                    'icon' => '🥤',
                    'badge' => 'High-Volume Refrigerated Sales',
                    'tagline' => 'Fast-Paced Energy Drinks & Bottled Beverages',
                    'desc' => 'Case packs of premium energy drinks, sodas, and chilled beverages delivered cold-chain ready for store refrigerators.',
                    'perks' => [
                        'Next-day route truck delivery direct to your store dock',
                        'Pallet & master case volume discount tiers',
                        'High-margin energy drinks and specialty sodas'
                    ]
                ],
                'general-merchandise-c-store' => [
                    'icon' => '🏪',
                    'badge' => 'Convenience Essentials',
                    'tagline' => 'Counter Impulse Trays & General Merchandise',
                    'desc' => 'Display trays of reliable maxi pocket lighters, charging cables, keychains, and essential c-store counter impulse goods.',
                    'perks' => [
                        'Pre-filled POP acrylic counter display stands',
                        'Consistent high customer repeat purchase frequency',
                        'One-invoice multi-department delivery'
                    ]
                ],
                default => [
                    'icon' => '📦',
                    'badge' => 'Wholesale Department',
                    'tagline' => 'Wholesale Department Highlights & Key SKUs',
                    'desc' => 'Hand-picked wholesale assortment designed for maximum turnover, zero shelf downtime, and strong retail demand.',
                    'perks' => [
                        'Factory-direct case rate pricing',
                        'Guaranteed authentic wholesale packaging',
                        'Free route delivery across the Carolinas'
                    ]
                ]
            };

            return [
                'id' => $cat->id,
                'slug' => $cat->slug,
                'name' => $cat->name,
                'icon' => $meta['icon'],
                'badge' => $meta['badge'],
                'tagline' => $meta['tagline'],
                'desc' => $meta['desc'],
                'perks' => $meta['perks'],
                'product' => $product,
                'sub_count' => $cat->children->count(),
                'product_count' => Product::whereIn('category_id', $childIds)->count(),
            ];
        });

        return view('frontend.home', compact('categories', 'featuredProducts', 'countries', 'solutions'));
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
