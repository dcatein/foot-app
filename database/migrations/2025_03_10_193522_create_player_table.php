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
        Schema::create('player', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('atk');
            $table->integer('mid');
            $table->integer('def');
            $table->integer('gol');
            $table->string('position');
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player');
    }
};
