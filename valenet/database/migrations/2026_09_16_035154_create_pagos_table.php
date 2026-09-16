<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->cascadeOnDelete();

            $table->foreignId('cliente_plan_id')
                ->nullable()
                ->constrained('cliente_planes')
                ->nullOnDelete();

            /*
             * Primer día del mes correspondiente.
             * Ejemplo:
             * 2026-09-01
             */
            $table->date('periodo');

            $table->date('fecha_vencimiento');

            $table->decimal('monto', 10, 2);

            $table->decimal('monto_pagado', 10, 2)
                ->default(0);

            $table->dateTime('fecha_pago')
                ->nullable();

            $table->string('metodo_pago', 30)
                ->nullable();

            $table->string('referencia', 100)
                ->nullable();

            $table->text('observacion')
                ->nullable();

            /*
             * pendiente
             * pagado
             * vencido
             * anulado
             */
            $table->string('estado', 20)
                ->default('pendiente');

            $table->timestamps();

            $table->index('cliente_id');

            $table->index('cliente_plan_id');

            $table->index('periodo');

            $table->index('fecha_vencimiento');

            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};