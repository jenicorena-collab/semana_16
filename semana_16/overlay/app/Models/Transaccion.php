<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaccion extends Model
{
    protected $fillable = ['comercio_id', 'cliente_nombre', 'monto', 'estado'];
    protected $casts = ['monto' => 'decimal:2'];
    public function comercio(): BelongsTo { return $this->belongsTo(Comercio::class); }
}
