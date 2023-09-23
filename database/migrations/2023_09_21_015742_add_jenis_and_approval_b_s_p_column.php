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
        Schema::table('bantuan', function (Blueprint $table) {
            $table->after('bukti', function(Blueprint $table){
                $table->string('jenis');
                $table->integer('approval_bsp');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bantuan', function (Blueprint $table) {
            $table->dropColumn(['jenis', 'approval_bsp']);
        });
    }
};
