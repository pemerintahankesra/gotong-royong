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
        Schema::create('detil_bantuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bantuan_id')->constrained(table : 'bantuan')->onUpdate('cascade')->onDelete('cascade');
            $table->string('kategori');
            $table->string('item');
            $table->string('jumlah');
            $table->string('nominal');
            $table->string('total_nominal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detil_bantuan');
    }
};
