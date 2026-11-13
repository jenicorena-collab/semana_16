<?php

namespace Tests\Feature;

use App\Models\Comercio;
use App\Models\Transaccion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FiltroTransaccionesTest extends TestCase
{
    use RefreshDatabase;

    public function test_filtro_requiere_autenticacion(): void
    {
        $this->getJson('/api/transacciones/filtrar')->assertUnauthorized();
    }

    public function test_usuario_solo_ve_sus_transacciones_y_filtra_por_monto(): void
    {
        $ana = User::factory()->create();
        $carlos = User::factory()->create();
        $comercioAna = Comercio::create(['user_id' => $ana->id, 'nombre_comercio' => 'Ana']);
        $comercioCarlos = Comercio::create(['user_id' => $carlos->id, 'nombre_comercio' => 'Carlos']);
        Transaccion::create(['comercio_id' => $comercioAna->id, 'cliente_nombre' => 'A', 'monto' => 50, 'estado' => 'Completada']);
        Transaccion::create(['comercio_id' => $comercioCarlos->id, 'cliente_nombre' => 'C', 'monto' => 90, 'estado' => 'Completada']);

        $this->actingAs($ana, 'sanctum')->getJson('/api/transacciones/filtrar?monto_min=20&monto_max=60')
            ->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.cliente', 'A');
    }

    public function test_estado_invalido_devuelve_422(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum')->getJson('/api/transacciones?estado=Pagada')->assertUnprocessable();
    }

    public function test_monto_maximo_sin_minimo_es_valido(): void
    {
        $user = User::factory()->create();
        Comercio::create(['user_id' => $user->id, 'nombre_comercio' => 'Comercio']);
        $this->actingAs($user, 'sanctum')->getJson('/api/transacciones/filtrar?monto_max=100')->assertOk();
    }

    public function test_monto_maximo_menor_que_minimo_es_invalido(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum')->getJson('/api/transacciones/filtrar?monto_min=50&monto_max=10')->assertUnprocessable();
    }
}
