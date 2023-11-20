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
            $table->foreignId('penerima_id')->nullable()->constrained(table: 'penerima');
            $table->string('kategori');
            $table->string('item');
            $table->integer('jumlah');
            $table->integer('nominal');
            $table->integer('total_nominal');
            $table->text('jenis');
            $table->text('foto_laporan')->nullable();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);

            $table->index('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detil_penarikan');
    }
};
