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
        Schema::table('league', function (Blueprint $table){
            $table->rename('championship');
            $table->string('format')->default('league');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('championship', function (Blueprint $table){
            $table->rename('league');
            $table->dropColumn('format');
        });
    }
};
