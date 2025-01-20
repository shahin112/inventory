<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateProduct;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{

    public function index(Request $request)
    {

        $storeId  = $request->user()->store_id;
        $products = Product::where('store_id', $storeId)->with('category')->get();
        $category = $this->getcategory($storeId);

        return Inertia::render("Product/Index", ['products' => $products, 'category' => $category]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'category_id' => 'required|numeric',
            'title' => 'required|string',
            'short_des' => 'nullable|string',
            'price' => 'required|string',
            'discount' => 'nullable|string',
            'image' => 'nullable|mimes:jpeg,png,jpg',
            'stock' => 'required|numeric',
            'store_id' => 'required',
        ]);

 

        $store_id = $request->store_id;

        $file      = $request->file('image');
        $imageName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('product/img'), $imageName);

        $product = Product::create([
            'store_id'    => $store_id,
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'short_des'   => $request->short_des,
            'price'       => $request->price,
            'discount'    => $request->discount,
            'image'       => $imageName,
            'stock'       => $request->stock,
        ]);

        return back()->with(['product' => $product]);

    }

    public function getcategory(String $store_id)
    {
         
        $categories = Category::where('store_id', $store_id)
            ->select('id as category_id', 'category_name')
            ->get();

        return $categories;
    }

}
