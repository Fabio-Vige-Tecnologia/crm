<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource {
    public function toArray(Request $request): array {
        /** @var \App\Models\Customer $customer */
        $customer = $this->resource;

        return [
            'id'         => $customer->id,
            'first_name' => $customer->first_name,
            'last_name'  => $customer->last_name,
            'email'      => $customer->email,
            'phone'      => $customer->phone,
            'created_at' => $customer->created_at,
            'updated_at' => $customer->updated_at,
            'deleted_at' => $customer->deleted_at,
        ];
    }
}
