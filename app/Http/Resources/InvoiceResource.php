<?php
namespace App\Http\Resources;

use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'store_id'       => $this->store_id,
            'invoice_id'     => $this->invoice_id,
            'created_at'     => $this->created_at->format('Y-m-d'),
            'user'           => new CustomerResource($this->whenLoaded('user')),
            'total_discount' => number_format($this->sales->sum('discount') ?? 0, 2),
            'total_vat'      => number_format($this->sales->sum('vat') ?? 0, 2),
            'payable'        => number_format(($this->sales->sum('total_price') ?? 0) + ($this->sales->sum('vat') ?? 0), 2),
            'total_price'    => number_format(
                ($this->sales->sum('total_price') ?? 0) +
                ($this->sales->sum('vat') ?? 0) +
                ($this->sales->sum('discount') ?? 0), 2
            ),
        ];

    }
}
