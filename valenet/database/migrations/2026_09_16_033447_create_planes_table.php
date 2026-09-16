<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planes', function (Blueprint $table) {

            $table->id();

            $table->string('nombre', 100);

            $table->string('velocidad', 50);

            $table->decimal('precio', 10, 2);

            $table->string('descripcion', 500)->nullable();

            $table->boolean('estado')->default(true);

            $table->timestamps();

            $table->index('nombre');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planes');
    }
};