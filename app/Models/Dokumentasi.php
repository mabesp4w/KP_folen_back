<?php
// app/Models/Dokumentasi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $table = 'dokumentasi';

    protected $fillable = [
        'judul',
        'deskripsi',
        'jenis',
        'file_dokumentasi',
        'url_embed',
        'thumbnail',
        'tgl_dokumentasi',
        'lokasi',
        'terdokumentasi_type',
        'terdokumentasi_id'
    ];

    protected $casts = [
        'tgl_dokumentasi' => 'date',
    ];

    // Polymorphic relationship
    public function terdokumentasi()
    {
        return $this->morphTo();
    }
}
