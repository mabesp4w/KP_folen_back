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
        Schema::create('dokumentasi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('jenis', ['foto', 'video']);
            $table->string('file_dokumentasi');
            $table->text('url_embed')->nullable(); // untuk video eksternal
            $table->string('thumbnail')->nullable();
            $table->date('tgl_dokumentasi')->nullable();
            $table->string('lokasi')->nullable();

            // Relasi polymorphic - bisa terkait dengan karya_musik atau jadwal_kegiatan
            $table->nullableMorphs('terdokumentasi');
            $table->timestamps();

            $table->index(['jenis', 'tgl_dokumentasi']);
            // $table->index(['terdokumentasi_type', 'terdokumentasi_id']);
            $table->index('tgl_dokumentasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi');
    }
};
