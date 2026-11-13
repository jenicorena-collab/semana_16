<?php

namespace Database\Seeders;

use App\Models\Comercio;
use App\Models\Transaccion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $ana = User::updateOrCreate(['email' => 'ana@taskboard.test'], ['name' => 'Ana', 'password' => Hash::make('Ana12345!')]);
        $carlos = User::updateOrCreate(['email' => 'carlos@taskboard.test'], ['name' => 'Carlos', 'password' => Hash::make('Carlos12345!')]);

        $comercioAna = Comercio::updateOrCreate(['user_id' => $ana->id], ['nombre_comercio' => 'Comercio de Ana']);
        $comercioCarlos = Comercio::updateOrCreate(['user_id' => $carlos->id], ['nombre_comercio' => 'Comercio de Carlos']);

        foreach ([
            [$comercioAna, 'Cliente Ana 1', 25.00, 'Completada'], [$comercioAna, 'Cliente Ana 2', 80.50, 'Iniciada'],
            [$comercioAna, 'Cliente Ana 3', 120.00, 'Fallida'], [$comercioAna, 'Cliente Ana 4', 55.00, 'Completada'],
            [$comercioCarlos, 'Cliente Carlos 1', 200.00, 'Completada'], [$comercioCarlos, 'Cliente Carlos 2', 15.00, 'Fallida'],
        ] as [$comercio, $cliente, $monto, $estado]) {
            Transaccion::firstOrCreate(['comercio_id' => $comercio->id, 'cliente_nombre' => $cliente], ['monto' => $monto, 'estado' => $estado]);
        }
    }
}
