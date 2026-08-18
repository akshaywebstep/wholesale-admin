<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // 1. Fetch only ACTIVE Parent categories with their active subcategories
        $categories = Category::whereNull('parent_id')
            ->where('status', 'ACTIVE')
            ->with(['children' => function ($query) {
                $query->where('status', 'ACTIVE');
            }])
            ->get();

        // 2. Format Response Data cleanly
        $data = $categories->map(function ($category) {
            return [
                'id'            => $category->id,
                'name'          => $category->name,
                'slug'          => $category->slug,
                'image'         => $category->image ? asset('storage/' . $category->image) : null,
                'subcategories' => $category->children->map(function ($sub) {
                    return [
                        'id'        => $sub->id,
                        'name'      => $sub->name,
                        'slug'      => $sub->slug,
                        'parent_id' => $sub->parent_id,
                        'image'     => $sub->image ? asset('storage/' . $sub->image) : null,
                    ];
                }),
            ];
        });

        // 3. Simple & Clean JSON Response
        return response()->json([
            'success' => true,
            'message' => 'Categories fetched successfully',
            'data'    => $data
        ]);
    }
}