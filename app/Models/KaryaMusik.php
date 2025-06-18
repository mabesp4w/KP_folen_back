<?php

// app/Models/KaryaMusik.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryaMusik extends Model
{
    use HasFactory;

    protected $table = 'karya_musik';

    protected $fillable = [
        'judul',
        'deskripsi',
        'nm_artis',
        'genre',
        'tgl_rilis',
        'url_video',
        'url_audio',
        'thumbnail'
    ];

    protected $casts = [
        'tgl_rilis' => 'date',
    ];

    // Relationship dengan kategori (many-to-many)
    public function kategori()
    {
        return $this->belongsToMany(Kategori::class, 'karya_musik_kategori');
    }

    // Relationship dengan dokumentasi (polymorphic)
    public function dokumentasi()
    {
        return $this->morphMany(Dokumentasi::class, 'terdokumentasi');
    }
}
