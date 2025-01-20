<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\StoreResource;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceHeaderResource extends JsonResource {

    public function toArray(Request $request): array {
        return [
            'invoice_number' => $this->invoice_id,
            'issue_date' => $this->created_at->format('Y-m-d'),
            'user' => new CustomerResource($this->whenLoaded('user')),
            'store' => new StoreResource($this->whenLoaded('store')),

        ];
    }
}
