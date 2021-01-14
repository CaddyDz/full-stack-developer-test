<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'char_code' => $this->char_code,
            'nominal' => $this->nominal,
            'name' => $this->name,
            'rate' => $this->rate,
        ];
    }
}
