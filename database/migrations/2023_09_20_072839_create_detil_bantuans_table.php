<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

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
            $table->softDeletes($column = 'deleted_at', $precision = 0);

            $table->index('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detil_bantuan');
    }
};
