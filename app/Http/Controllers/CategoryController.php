<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::where('store_id', $request->user()->store_id)
            ->paginate(5);

        // Append product_total to each category item in the paginated result
        $categories->getCollection()->transform(function ($category) use ($request) {
            $product_total = Product::where('store_id', $request->user()->store_id)
                ->where('category_id', $category->id)
                ->sum('stock');

            return [
                'category_id' => $category->id,
                'category_name' => $category->category_name,
                'product_total' => $product_total,
            ];
        });
        return Inertia::render("Category/Index", ['categories' => $categories]);

    }

    public function store(Request $request) {

        $request->validate([

            'category_img' => 'nullable|string',

            'category_name' => [
                'required',
                'string',
                Rule::unique('categories')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->user()->store_id);
                }),
            ],
        ]);

        $category = Category::create([
            'store_id' => $request->user()->store_id,
            'category_name' => $request->category_name,
            // 'category_img' => $request->category_img,

        ]);

        return back()->with([

            'category' => $category,
        ]);

    }


    
}
