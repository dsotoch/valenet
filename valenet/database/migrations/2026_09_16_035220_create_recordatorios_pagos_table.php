<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recordatorios_pagos', function (Blueprint $table) {
            $table->id();

            // Pago relacionado
            $table->foreignId('pago_id')
                ->constrained('pagos')
                ->cascadeOnDelete();

            // Canal utilizado para el recordatorio
            // whatsapp, email, sms, etc.
            $table->string('canal', 30)->default('whatsapp');

            // Tipo de envío
            // manual, automatico, vencimiento, proximo_vencimiento, etc.
            $table->string('tipo', 50)->default('manual');

            // Fecha en que se inició/envió el recordatorio
            $table->dateTime('fecha_envio')->nullable();

            // Mensaje enviado
            $table->text('mensaje')->nullable();

            $table->timestamps();

            // Índices
            $table->index('pago_id');
            $table->index('canal');
            $table->index('tipo');
            $table->index('fecha_envio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recordatorios_pagos');
    }
};