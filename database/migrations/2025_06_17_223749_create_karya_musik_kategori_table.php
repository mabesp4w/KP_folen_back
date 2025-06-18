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
        Schema::create('karya_musik_kategori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karya_musik_id')->constrained('karya_musik')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['karya_musik_id', 'kategori_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karya_musik_kategori');
    }
};
