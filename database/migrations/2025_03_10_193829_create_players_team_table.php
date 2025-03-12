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
        Schema::create('players_team', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('player_id');
            $table->bigInteger('team_id');
            $table->foreign('player_id')->references('id')->on('player');
            $table->foreign('team_id')->references('id')->on('team');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players_team');
    }
};
