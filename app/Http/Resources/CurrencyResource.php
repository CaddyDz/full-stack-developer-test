<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nominal' => $this->nominal,
            'name' => $this->name,
            'rate' => $this->rate,
        ];
    }
}
