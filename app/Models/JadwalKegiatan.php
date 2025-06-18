<?php
// app/Models/JadwalKegiatan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKegiatan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kegiatan';

    protected $fillable = [
        'judul',
        'deskripsi',
        'jenis',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'harga',
        'maksimal_peserta',
        'peserta_saat_ini',
        'status',
        'catatan'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'harga' => 'decimal:2',
        'maksimal_peserta' => 'integer',
        'peserta_saat_ini' => 'integer',
    ];

    // Relationship dengan kategori (many-to-many)
    public function kategori()
    {
        return $this->belongsToMany(Kategori::class, 'jadwal_kategori');
    }

    // Relationship dengan dokumentasi (polymorphic)
    public function dokumentasi()
    {
        return $this->morphMany(Dokumentasi::class, 'terdokumentasi');
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'terjadwal' => 'warning',
            'berlangsung' => 'info',
            'selesai' => 'success',
            'dibatalkan' => 'danger'
        ];
        return $badges[$this->status] ?? 'secondary';
    }
}
