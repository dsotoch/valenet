<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cliente_planes', function (Blueprint $table) {

            $table->id();

            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->cascadeOnDelete();

            $table->foreignId('plan_id')
                ->constrained('planes')
                ->restrictOnDelete();

            $table->date('fecha_inicio');

            $table->date('fecha_fin')
                ->nullable();

            /*
             * Guardamos el precio contratado.
             * No dependemos del precio actual del plan.
             */
            $table->decimal('precio', 10, 2);

            $table->boolean('estado')
                ->default(true);

            $table->timestamps();

            $table->index([
                'cliente_id',
                'estado'
            ]);

            $table->index([
                'plan_id',
                'estado'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_planes');
    }
};