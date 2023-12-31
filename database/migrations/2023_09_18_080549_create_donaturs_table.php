<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('donatur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('nama');
            $table->text('alamat');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            
            $table->index('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donatur');
    }
};
