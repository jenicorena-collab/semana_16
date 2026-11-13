<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comercio extends Model
{
    protected $fillable = ['user_id', 'nombre_comercio'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function transacciones(): HasMany { return $this->hasMany(Transaccion::class); }
}
