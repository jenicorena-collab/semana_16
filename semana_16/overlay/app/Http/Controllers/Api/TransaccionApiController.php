<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransaccionResource;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransaccionApiController extends Controller
{
    private const ESTADOS = ['Iniciada', 'Completada', 'Fallida'];

    public function index(Request $request)
    {
        $datos = $request->validate([
            'estado' => ['nullable', Rule::in(self::ESTADOS)],
        ]);

        $consulta = Transaccion::query()
            ->whereHas('comercio', fn ($q) => $q->where('user_id', $request->user()->id))
            ->when($datos['estado'] ?? null, fn ($q, $estado) => $q->where('estado', $estado))
            ->latest();

        return TransaccionResource::collection($consulta->get());
    }

    public function filtrar(Request $request)
    {
        $datos = $request->validate([
            'estado' => ['nullable', Rule::in(self::ESTADOS)],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
            'monto_min' => ['nullable', 'numeric', 'min:0'],
            'monto_max' => ['nullable', 'numeric', 'min:0', Rule::when($request->filled('monto_min'), ['gte:monto_min'])],
        ]);

        $consulta = Transaccion::query()
            ->whereHas('comercio', fn ($q) => $q->where('user_id', $request->user()->id))
            ->when($datos['estado'] ?? null, fn ($q, $estado) => $q->where('estado', $estado))
            ->when($datos['desde'] ?? null, fn ($q, $fecha) => $q->whereDate('created_at', '>=', $fecha))
            ->when($datos['hasta'] ?? null, fn ($q, $fecha) => $q->whereDate('created_at', '<=', $fecha))
            ->when($datos['monto_min'] ?? null, fn ($q, $monto) => $q->where('monto', '>=', $monto))
            ->when($datos['monto_max'] ?? null, fn ($q, $monto) => $q->where('monto', '<=', $monto))
            ->latest();

        return TransaccionResource::collection($consulta->get());
    }

    public function show(Request $request, Transaccion $transaccion)
    {
        abort_unless($transaccion->comercio()->where('user_id', $request->user()->id)->exists(), 404);
        return new TransaccionResource($transaccion);
    }
}
