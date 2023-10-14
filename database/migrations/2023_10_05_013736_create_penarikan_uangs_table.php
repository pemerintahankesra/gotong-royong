<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('penarikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained(table : 'program')->onUpdate('cascade');
            $table->foreignId('tagged_by')->constrained(table : 'users')->onUpdate('cascade');
            $table->date('tanggal_pengajuan');
            $table->text('surat_pengajuan');
            $table->text('rekening_tujuan_pencairan');
            $table->date('tanggal_pencairan')->nullable();
            $table->integer('approval_bsp')->default(0);
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penarikan');
    }
};
