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
        Schema::create('match', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('league_id');
            $table->bigInteger('team_home_id');
            $table->bigInteger('team_away_id');
            $table->foreign('league_id')->references('id')->on('league');
            $table->foreign('team_home_id')->references('id')->on('team');
            $table->foreign('team_away_id')->references('id')->on('team');
            $table->integer('team_home_score');
            $table->integer('team_away_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match');
    }
};
