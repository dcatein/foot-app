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
        Schema::table('match', function (Blueprint $table) {
            $table->renameColumn('league_id', 'championship_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match', function (Blueprint $table) {
            $table->renameColumn('championship_id', 'league_id');
        });
    }
};
