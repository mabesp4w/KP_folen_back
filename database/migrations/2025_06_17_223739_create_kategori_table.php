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
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nm_kategori');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->enum('jenis', ['musik', 'acara', 'dokumentasi']); // kategori untuk apa
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->index(['jenis', 'aktif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};
