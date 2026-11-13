<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comercio_id')->constrained('comercios')->cascadeOnDelete();
            $table->string('cliente_nombre');
            $table->decimal('monto', 10, 2);
            $table->string('estado', 20);
            $table->timestamps();
            $table->index(['estado', 'created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('transacciones'); }
};
