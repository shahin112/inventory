<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellProductRequest;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CreateSaleController extends Controller
{
    public function index(Request $request)
    {

        $product = Product::where('store_id', $request->user()->store_id)->where('stock', '>', 0)->get();

        return Inertia::render("CreateSale/Index", ['products' => $product, 'user' => ""]);
    }

    public function searchCustomer(Request $request, $query)
    {

        $storeId = $request->user()->store_id;

        $users = User::where('store_id', $storeId)
            ->where('role', '0')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('profiles.first_name', 'LIKE', '%' . $query . '%')->orWhere('mobile', $query)
            ->select('users.id', 'profiles.first_name', 'profiles.last_name')
            ->get();

        return response()->json(['customers' => $users], 200);

    }

    public function sellProducts(Request $request)
    {

         $request->validate([
            'user_id' => 'required|numeric',
            'discount' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product_id' => 'required|numeric',
            'products.*.quantity' => 'required|numeric',
            'store_id' => 'required',
         ]);

      

        $product = null;

        DB::transaction(function () use ($request, &$product) {
            $invoiceId = $this->generateInvoiceId();

            $overallDiscount = $request->discount ?? 0;

            $invoice = Invoice::create([
                'store_id' => $request->store_id,
                'user_id' => $request->user_id,

                'invoice_id' => $invoiceId,
            ]);

            $totalAmountWithoutDiscount = 0;
            foreach ($request->products as $productData) {
                $product = Product::find($productData['product_id']);
                if (!$product) {
                    throw new \Exception('Product not found: ' . $productData['product_id']);
                }
                if ($product->stock < $productData['quantity']) {
                    throw new \Exception('Insufficient stock for product ' . $product->title . '. Only ' . $product->stock . ' items available.');
                }

                $totalAmountWithoutDiscount += $productData['quantity'] * $product->price;
            }

            foreach ($request->products as $productData) {
                $product = Product::find($productData['product_id']);

                $productTotal = $productData['quantity'] * $product->price;

                $proportionalDiscount = ($productTotal / $totalAmountWithoutDiscount) * $overallDiscount;

                $totalAfterDiscount = $productTotal - $proportionalDiscount;

                $vat = $totalAfterDiscount * 0.05;

                $totalAfterVat = $totalAfterDiscount - $vat;

                Sale::create([

                    'product_id' => $product->id,
                    'invoice_id' => $invoice->id,
                    'store_id' => $request->store_id,
                    'quantity' => $productData['quantity'],
                    'discount' => $proportionalDiscount,
                    'vat' => $vat,
                    'total_price' => $totalAfterVat,
                ]);

                $product->update([
                    'stock' => $product->stock - $productData['quantity'],
                ]);
            }
        });

        return back()->with(['product' => $product]);
    }

    public function generateInvoiceId()
    {

        $lastInvoice = Invoice::orderBy('id', 'desc')->first();

        if ($lastInvoice) {
            $nextId = (int) $lastInvoice->invoice_id + 1;
        } else {
            $nextId = 1;
        }

        return str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }

}
