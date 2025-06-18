<?php
// app/Models/Kategori.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'nm_kategori',
        'slug',
        'deskripsi',
        'jenis',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Relationship dengan karya musik (many-to-many)
    public function karyaMusik()
    {
        return $this->belongsToMany(KaryaMusik::class, 'karya_musik_kategori');
    }

    // Relationship dengan jadwal kegiatan (many-to-many)
    public function jadwalKegiatan()
    {
        return $this->belongsToMany(JadwalKegiatan::class, 'jadwal_kategori');
    }

    // Scope untuk kategori aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Scope berdasarkan jenis
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }
}
