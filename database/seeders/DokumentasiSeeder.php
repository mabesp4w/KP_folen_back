<?php

namespace Database\Seeders;

use App\Models\Dokumentasi;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DokumentasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $dokumentasi = [
            // Dokumentasi terkait Karya Musik
            [
                'judul' => 'Behind the Scene - Mimpi di Awan',
                'deskripsi' => 'Proses rekaman dan pembuatan video klip',
                'jenis' => 'foto',
                'file_dokumentasi' => 'dokumentasi/foto/bts-mimpi-di-awan-001.jpg',
                'thumbnail' => 'dokumentasi/foto/thumbs/bts-mimpi-di-awan-001.jpg',
                'tgl_dokumentasi' => '2024-01-10',
                'lokasi' => 'Studio A',
                'terdokumentasi_type' => 'App\\Models\\KaryaMusik',
                'terdokumentasi_id' => 1,
            ],
            [
                'judul' => 'Music Video - Mimpi di Awan',
                'deskripsi' => 'Video musik official dari single Mimpi di Awan',
                'jenis' => 'video',
                'file_dokumentasi' => 'dokumentasi/video/mv-mimpi-di-awan.mp4',
                'url_embed' => 'https://youtube.com/embed/abc123',
                'thumbnail' => 'dokumentasi/video/thumbs/mv-mimpi-di-awan.jpg',
                'tgl_dokumentasi' => '2024-01-15',
                'lokasi' => 'Studio A & Location',
                'terdokumentasi_type' => 'App\\Models\\KaryaMusik',
                'terdokumentasi_id' => 1,
            ],
            [
                'judul' => 'Recording Session - The Wanderers',
                'deskripsi' => 'Dokumentasi sesi rekaman album Petualangan Malam',
                'jenis' => 'foto',
                'file_dokumentasi' => 'dokumentasi/foto/rec-wanderers-001.jpg',
                'thumbnail' => 'dokumentasi/foto/thumbs/rec-wanderers-001.jpg',
                'tgl_dokumentasi' => '2024-02-15',
                'lokasi' => 'Studio B',
                'terdokumentasi_type' => 'App\\Models\\KaryaMusik',
                'terdokumentasi_id' => 2,
            ],
            [
                'judul' => 'Jazz Session - Maya Sari Trio',
                'deskripsi' => 'Dokumentasi live recording session',
                'jenis' => 'video',
                'file_dokumentasi' => 'dokumentasi/video/jazz-session-maya.mp4',
                'thumbnail' => 'dokumentasi/video/thumbs/jazz-session-maya.jpg',
                'tgl_dokumentasi' => '2024-03-05',
                'lokasi' => 'Studio A',
                'terdokumentasi_type' => 'App\\Models\\KaryaMusik',
                'terdokumentasi_id' => 3,
            ],

            // Dokumentasi terkait Jadwal Kegiatan
            [
                'judul' => 'Workshop Produksi Musik - Day 1',
                'deskripsi' => 'Foto-foto kegiatan workshop hari pertama',
                'jenis' => 'foto',
                'file_dokumentasi' => 'dokumentasi/foto/workshop-produksi-001.jpg',
                'thumbnail' => 'dokumentasi/foto/thumbs/workshop-produksi-001.jpg',
                'tgl_dokumentasi' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'lokasi' => 'Studio A - Lantai 2',
                'terdokumentasi_type' => 'App\\Models\\JadwalKegiatan',
                'terdokumentasi_id' => 1,
            ],
            [
                'judul' => 'Masterclass Jazz Legend',
                'deskripsi' => 'Video highlight dari masterclass',
                'jenis' => 'video',
                'file_dokumentasi' => 'dokumentasi/video/masterclass-jazz.mp4',
                'thumbnail' => 'dokumentasi/video/thumbs/masterclass-jazz.jpg',
                'tgl_dokumentasi' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'lokasi' => 'Main Hall',
                'terdokumentasi_type' => 'App\\Models\\JadwalKegiatan',
                'terdokumentasi_id' => 2,
            ],
            [
                'judul' => 'Open Mic Night - Performers',
                'deskripsi' => 'Foto para performer di open mic night',
                'jenis' => 'foto',
                'file_dokumentasi' => 'dokumentasi/foto/open-mic-performers.jpg',
                'thumbnail' => 'dokumentasi/foto/thumbs/open-mic-performers.jpg',
                'tgl_dokumentasi' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'lokasi' => 'Cafe Stage',
                'terdokumentasi_type' => 'App\\Models\\JadwalKegiatan',
                'terdokumentasi_id' => 3,
            ],

            // Dokumentasi standalone (tidak terkait ke model tertentu)
            [
                'judul' => 'Studio Tour 2024',
                'deskripsi' => 'Virtual tour fasilitas studio musik',
                'jenis' => 'video',
                'file_dokumentasi' => 'dokumentasi/video/studio-tour-2024.mp4',
                'url_embed' => 'https://youtube.com/embed/studiotour2024',
                'thumbnail' => 'dokumentasi/video/thumbs/studio-tour-2024.jpg',
                'tgl_dokumentasi' => '2024-01-01',
                'lokasi' => 'Seluruh Area Studio',
                'terdokumentasi_type' => null,
                'terdokumentasi_id' => null,
            ],
            [
                'judul' => 'Equipment Showcase',
                'deskripsi' => 'Foto-foto peralatan studio yang tersedia',
                'jenis' => 'foto',
                'file_dokumentasi' => 'dokumentasi/foto/equipment-showcase.jpg',
                'thumbnail' => 'dokumentasi/foto/thumbs/equipment-showcase.jpg',
                'tgl_dokumentasi' => '2024-01-05',
                'lokasi' => 'Control Room',
                'terdokumentasi_type' => null,
                'terdokumentasi_id' => null,
            ],
            [
                'judul' => 'Team Profile 2024',
                'deskripsi' => 'Foto profil tim studio musik',
                'jenis' => 'foto',
                'file_dokumentasi' => 'dokumentasi/foto/team-profile-2024.jpg',
                'thumbnail' => 'dokumentasi/foto/thumbs/team-profile-2024.jpg',
                'tgl_dokumentasi' => '2024-02-01',
                'lokasi' => 'Main Hall',
                'terdokumentasi_type' => null,
                'terdokumentasi_id' => null,
            ],
        ];

        foreach ($dokumentasi as $dok) {
            Dokumentasi::create($dok);
        }
    }
}
