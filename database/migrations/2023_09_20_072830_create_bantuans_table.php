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
        Schema::create('bantuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donatur_id')->constrained(table : 'donatur')->onUpdate('cascade');
            $table->foreignId('tagged_by')->constrained(table : 'users')->onUpdate('cascade');
            $table->foreignId('program_id')->constrained(table : 'program')->onUpdate('cascade');
            $table->date('tanggal');
            $table->string('bukti');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bantuan');
    }
};
