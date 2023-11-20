<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('penerima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('nik');
            $table->string('namalengkap');
            $table->string('alamatdomisili')->nullable();
            $table->text('kecamatandomisili')->nullable();
            $table->text('kelurahandomisili')->nullable();
            $table->string('alamatktp')->nullable();
            $table->text('kecamatanktp')->nullable();
            $table->text('kelurahanktp')->nullable();
            $table->string('flag_surabaya', 1);
            $table->timestamps();

            $table->index('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerima');
    }
};
