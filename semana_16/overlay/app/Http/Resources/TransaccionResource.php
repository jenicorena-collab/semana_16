<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaccionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cliente' => $this->cliente_nombre,
            'monto' => $this->monto,
            'estado' => $this->estado,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
