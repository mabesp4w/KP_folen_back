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
        Schema::create('karya_musik', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('nm_artis');
            $table->string('genre')->nullable();
            $table->date('tgl_rilis')->nullable();
            $table->text('url_video')->nullable(); // YouTube, Vimeo, dll
            $table->text('url_audio')->nullable(); // SoundCloud, Spotify, dll
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karya_musik');
    }
};
