<?php
namespace App\Http\Controllers;

use App\Http\Resources\InvoiceHeaderResource;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\Sale;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{

    public function index(Request $request)
    {

        $invoice_id = $request->query('invoice_id');
        $store_id   = $request->user()->store_id;

        $query = Invoice::where('store_id', $store_id)
            ->with(['sales', 'user.profile'])
            ->orderBy('created_at', 'desc');

        if ($invoice_id) {
            $query->where('invoice_id', $invoice_id);
        }

        $invoices = $query->distinct()->paginate(5);

        return Inertia::render("Invoice/Index", ['invoice' => InvoiceResource::collection($invoices)]);

    }

    public function invoice_info(Request $request, $invoice_id)
    {

        $invoices = Invoice::findOrFail($invoice_id);

        $this->authorize('view', $invoices);

        $storeId = $request->user()->store_id;

        $sale = Sale::where('store_id', $storeId)->where('invoice_id', $invoice_id);

        //===================================== header info===========================================
        $invoice = Invoice::where('id', $invoice_id)->where('store_id', $storeId)->with(['user.profile', 'store.user.profile'])->first();

        //========================================== items info=====================================================

        $invoiceItems = $sale->get(['product_id', 'quantity', 'vat', 'total_price', 'discount']);

        $formattedItems = $invoiceItems->map(function ($item) {
            return [
                'product_id'     => $item->product_id,
                'product_name'   => $item->product->title,
                'original_price' => $item->product->price,
                'quantity'       => $item->quantity,
                'vat'            => number_format($item->vat, 2),
                'price'          => number_format($item->total_price + $item->discount, 2),
            ];
        });

        //===================================== calculation =========================================

        $discount    = $sale->sum('discount');
        $total_vat   = $sale->sum('vat');
        $total_price = $sale->sum('total_price');

        $invoice_data = [
            'header_info' => new InvoiceHeaderResource($invoice),
            'items'       => $formattedItems,
            'discount'    => number_format($discount, 2),
            'total_vat'   => number_format($total_vat, 2),
            'total_price' => number_format($total_price + $total_vat, 2),
            'sub_total'   => number_format($total_price + $total_vat + $discount, 2),

        ];

        return response()->json(['data' => $invoice_data], 200);

    }

}
