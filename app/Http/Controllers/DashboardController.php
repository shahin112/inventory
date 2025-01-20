<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{

    public function index(Request $request)
    {

        $store_id = $request->user()->store_id;

        $totalSales = Sale::where('store_id', $store_id)->sum('total_price');
        $total_vat = Sale::where('store_id', $store_id)->sum('vat');
        $total_collection = $total_vat + $totalSales;

        $data = [
            ['title' => 'Products', 'value' => Product::where('store_id', $store_id)->count()],
            ['title' => 'Categories', 'value' => Category::where('store_id', $store_id)->count()],
            ['title' => 'Customers', 'value' => User::where('store_id', $store_id)->where('role', '0')->count()],
            ['title' => 'Invoices', 'value' => Invoice::where('store_id', $store_id)->count()],
            ['title' => 'Total Sales', 'value' => '$' . number_format($totalSales, 2)],
            ['title' => 'Total Discount', 'value' => '$' . number_format(Sale::where('store_id', $store_id)->sum('discount'), 2)],
            ['title' => 'Total Vat', 'value' => '$' . number_format($total_vat, 2)],
            ['title' => 'Total Collection', 'value' => '$' . number_format($total_collection, 2)],
        ];

        return Inertia::render("Dashboard/Index", ['data' => $data]);

    }
}
