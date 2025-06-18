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
        Schema::create('jadwal_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('jenis', ['acara', 'sesi_studio', 'konser', 'rekaman']);
            $table->datetime('waktu_mulai');
            $table->datetime('waktu_selesai');
            $table->string('lokasi')->nullable();
            $table->decimal('harga', 10, 2)->nullable();
            $table->integer('maksimal_peserta')->nullable();
            $table->integer('peserta_saat_ini')->default(0);
            $table->enum('status', ['terjadwal', 'berlangsung', 'selesai', 'dibatalkan'])->default('terjadwal');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['jenis', 'waktu_mulai']);
            $table->index(['status', 'waktu_mulai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_kegiatan');
    }
};
