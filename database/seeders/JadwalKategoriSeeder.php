<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JadwalKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get kategori acara IDs
        $kategoriAcara = Kategori::where('jenis', 'acara')->get();
        $workshopId = $kategoriAcara->where('nm_kategori', 'Workshop')->first()->id;
        $masterclassId = $kategoriAcara->where('nm_kategori', 'Masterclass')->first()->id;
        $openMicId = $kategoriAcara->where('nm_kategori', 'Open Mic')->first()->id;
        $albumLaunchId = $kategoriAcara->where('nm_kategori', 'Album Launch')->first()->id;
        $showcaseId = $kategoriAcara->where('nm_kategori', 'Showcase')->first()->id;
        $networkingId = $kategoriAcara->where('nm_kategori', 'Networking')->first()->id;

        // Mapping jadwal kegiatan dengan kategori
        $mappings = [
            1 => [$workshopId], // Workshop Produksi Musik Digital
            2 => [$masterclassId], // Masterclass dengan Jazz Legend
            3 => [$openMicId], // Open Mic Night
            4 => [], // Recording Session - tidak perlu kategori acara
            5 => [], // Konser Akustik - tidak perlu kategori acara
            6 => [], // Studio Session - tidak perlu kategori acara
            7 => [$albumLaunchId], // Album Launch - Digital Dreams
            8 => [$networkingId], // Networking Session
            9 => [], // Recording Session - Maya Sari Trio
            10 => [$showcaseId], // Showcase New Artists
        ];

        foreach ($mappings as $jadwalKegiatanId => $kategoriIds) {
            foreach ($kategoriIds as $kategoriId) {
                DB::table('jadwal_kategori')->insert([
                    'jadwal_kegiatan_id' => $jadwalKegiatanId,
                    'kategori_id' => $kategoriId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
