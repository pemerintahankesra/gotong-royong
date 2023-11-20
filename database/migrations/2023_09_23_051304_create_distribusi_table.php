<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('distribusi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained(table : 'program')->onUpdate('cascade');
            $table->foreignId('tagged_by')->constrained(table : 'users')->onUpdate('cascade');
            $table->date('tanggal');
            $table->integer('status_laporan')->default(0);
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->index('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribusi');
    }
};
