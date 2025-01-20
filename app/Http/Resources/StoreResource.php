<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource {

    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'name' => ucfirst($this->store_name),
            'info' => [
                'logo' => $request->user()->mobile,
                'address' => ucfirst($request->user()->profile->address),

            ],
        ];

    }
}
