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
        Schema::create('detil_distribusi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribusi_id')->constrained(table : 'distribusi')->onDelete('cascade')->onUpdate('cascade');
            $table->string('kategori');
            $table->string('item');
            $table->integer('jumlah');
            $table->integer('nominal');
            $table->integer('total_nominal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detil_distribusi');
    }
};
