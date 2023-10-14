<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('detil_penarikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penarikan_id')->constrained(table : 'penarikan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('penerima_id')->constrained(table : 'penerima')->nullable();
            $table->string('kategori');
            $table->string('item');
            $table->integer('jumlah');
            $table->integer('nominal');
            $table->integer('total_nominal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detil_penarikan');
    }
};
