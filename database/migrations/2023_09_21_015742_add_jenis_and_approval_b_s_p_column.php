<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('bantuan', function (Blueprint $table) {
            $table->after('bukti', function(Blueprint $table){
                $table->string('jenis')->nullable();
                $table->integer('approval_bsp')->default(0);
            });
        });
    }

    public function down(): void
    {
        Schema::table('bantuan', function (Blueprint $table) {
            $table->dropColumn(['jenis', 'approval_bsp']);
        });
    }
};
