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
            $table->string('alamatdomisili');
            $table->text('kecamatandomisili');
            $table->text('kelurahandomisili');
            $table->string('alamatktp');
            $table->text('kecamatanktp');
            $table->text('kelurahanktp');
            $table->string('flag_surabaya', 1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerima');
    }
};
