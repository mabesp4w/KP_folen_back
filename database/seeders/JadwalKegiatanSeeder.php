<?php

namespace Database\Seeders;

use App\Models\JadwalKegiatan;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JadwalKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $jadwalKegiatan = [
            [
                'judul' => 'Workshop Produksi Musik Digital',
                'deskripsi' => 'Belajar teknik produksi musik menggunakan DAW modern',
                'jenis' => 'acara',
                'waktu_mulai' => $now->copy()->addDays(5)->setTime(10, 0),
                'waktu_selesai' => $now->copy()->addDays(5)->setTime(16, 0),
                'lokasi' => 'Studio A - Lantai 2',
                'harga' => 250000,
                'maksimal_peserta' => 20,
                'peserta_saat_ini' => 15,
                'status' => 'terjadwal',
                'catatan' => 'Peserta wajib membawa laptop',
            ],
            [
                'judul' => 'Masterclass dengan Jazz Legend',
                'deskripsi' => 'Kelas master bersama musisi jazz ternama',
                'jenis' => 'acara',
                'waktu_mulai' => $now->copy()->addDays(10)->setTime(19, 0),
                'waktu_selesai' => $now->copy()->addDays(10)->setTime(21, 30),
                'lokasi' => 'Main Hall',
                'harga' => 150000,
                'maksimal_peserta' => 50,
                'peserta_saat_ini' => 35,
                'status' => 'terjadwal',
                'catatan' => 'Termasuk meet & greet',
            ],
            [
                'judul' => 'Open Mic Night',
                'deskripsi' => 'Acara open mic untuk musisi lokal',
                'jenis' => 'acara',
                'waktu_mulai' => $now->copy()->addDays(7)->setTime(20, 0),
                'waktu_selesai' => $now->copy()->addDays(7)->setTime(23, 0),
                'lokasi' => 'Cafe Stage',
                'harga' => 0,
                'maksimal_peserta' => 15,
                'peserta_saat_ini' => 12,
                'status' => 'terjadwal',
                'catatan' => 'Gratis untuk performer, minimal 2 lagu',
            ],
            [
                'judul' => 'Recording Session - Andika Pratama',
                'deskripsi' => 'Sesi rekaman album kedua Andika Pratama',
                'jenis' => 'rekaman',
                'waktu_mulai' => $now->copy()->addDays(3)->setTime(14, 0),
                'waktu_selesai' => $now->copy()->addDays(3)->setTime(18, 0),
                'lokasi' => 'Studio B',
                'harga' => null,
                'maksimal_peserta' => null,
                'peserta_saat_ini' => 0,
                'status' => 'terjadwal',
                'catatan' => 'Private session',
            ],
            [
                'judul' => 'Konser Akustik Luna & The Stars',
                'deskripsi' => 'Konser intimate dengan format akustik',
                'jenis' => 'konser',
                'waktu_mulai' => $now->copy()->addDays(14)->setTime(19, 30),
                'waktu_selesai' => $now->copy()->addDays(14)->setTime(21, 30),
                'lokasi' => 'Acoustic Room',
                'harga' => 100000,
                'maksimal_peserta' => 80,
                'peserta_saat_ini' => 65,
                'status' => 'terjadwal',
                'catatan' => 'Standing room available',
            ],
            [
                'judul' => 'Studio Session - The Wanderers',
                'deskripsi' => 'Sesi latihan band sebelum tur',
                'jenis' => 'sesi_studio',
                'waktu_mulai' => $now->copy()->addDays(2)->setTime(16, 0),
                'waktu_selesai' => $now->copy()->addDays(2)->setTime(20, 0),
                'lokasi' => 'Studio C',
                'harga' => null,
                'maksimal_peserta' => null,
                'peserta_saat_ini' => 0,
                'status' => 'berlangsung',
                'catatan' => 'Persiapan tour',
            ],
            [
                'judul' => 'Album Launch - Digital Dreams',
                'deskripsi' => 'Peluncuran album terbaru DJ Elektro',
                'jenis' => 'acara',
                'waktu_mulai' => $now->copy()->addDays(21)->setTime(20, 0),
                'waktu_selesai' => $now->copy()->addDays(21)->setTime(23, 30),
                'lokasi' => 'Main Hall',
                'harga' => 200000,
                'maksimal_peserta' => 200,
                'peserta_saat_ini' => 45,
                'status' => 'terjadwal',
                'catatan' => 'Include exclusive merchandise',
            ],
            [
                'judul' => 'Networking Session - Music Industry',
                'deskripsi' => 'Acara networking untuk profesional musik',
                'jenis' => 'acara',
                'waktu_mulai' => $now->copy()->addDays(12)->setTime(18, 0),
                'waktu_selesai' => $now->copy()->addDays(12)->setTime(21, 0),
                'lokasi' => 'Lounge Area',
                'harga' => 75000,
                'maksimal_peserta' => 40,
                'peserta_saat_ini' => 28,
                'status' => 'terjadwal',
                'catatan' => 'Termasuk dinner & drinks',
            ],
            [
                'judul' => 'Recording Session - Maya Sari Trio',
                'deskripsi' => 'Rekaman album jazz live session',
                'jenis' => 'rekaman',
                'waktu_mulai' => $now->copy()->subDays(5)->setTime(10, 0),
                'waktu_selesai' => $now->copy()->subDays(5)->setTime(15, 0),
                'lokasi' => 'Studio A',
                'harga' => null,
                'maksimal_peserta' => null,
                'peserta_saat_ini' => 0,
                'status' => 'selesai',
                'catatan' => 'Live recording session',
            ],
            [
                'judul' => 'Showcase New Artists',
                'deskripsi' => 'Platform untuk artis baru memperkenalkan karya',
                'jenis' => 'acara',
                'waktu_mulai' => $now->copy()->subDays(10)->setTime(19, 0),
                'waktu_selesai' => $now->copy()->subDays(10)->setTime(22, 0),
                'lokasi' => 'Small Stage',
                'harga' => 50000,
                'maksimal_peserta' => 60,
                'peserta_saat_ini' => 55,
                'status' => 'selesai',
                'catatan' => 'Platform untuk talent scouting',
            ],
        ];

        foreach ($jadwalKegiatan as $jadwal) {
            JadwalKegiatan::create($jadwal);
        }
    }
}
