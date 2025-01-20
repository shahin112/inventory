<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'mobile' => $this->mobile,
            'profile'=>new ProfileResource($this->whenLoaded('profile'))
        ];
    }
}
