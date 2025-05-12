<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservaciones', function (Blueprint $table) {
            $table->id();
            $table->string('producto');
            $table->string('nombre_cliente');
            $table->integer('cantidad');
            $table->text('comentario')->nullable();
            $table->dateTime('fecha_reservacion'); // permite hora y fecha elegida
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservaciones'); // corregido
    }
};
