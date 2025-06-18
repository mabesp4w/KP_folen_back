<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriMusik = [
            ['nm_kategori' => 'Pop', 'deskripsi' => 'Musik populer dengan melodi yang mudah diingat'],
            ['nm_kategori' => 'Rock', 'deskripsi' => 'Musik rock dengan beat yang kuat'],
            ['nm_kategori' => 'Jazz', 'deskripsi' => 'Musik jazz dengan improvisasi'],
            ['nm_kategori' => 'Blues', 'deskripsi' => 'Musik blues dengan nuansa sedih'],
            ['nm_kategori' => 'Folk', 'deskripsi' => 'Musik tradisional'],
            ['nm_kategori' => 'Electronic', 'deskripsi' => 'Musik elektronik modern'],
        ];

        $kategoriAcara = [
            ['nm_kategori' => 'Konser Musik', 'deskripsi' => 'Pertunjukan musik live'],
            ['nm_kategori' => 'Workshop', 'deskripsi' => 'Pelatihan dan pembelajaran musik'],
            ['nm_kategori' => 'Open Mic', 'deskripsi' => 'Acara terbuka untuk musisi'],
            ['nm_kategori' => 'Rekaman Studio', 'deskripsi' => 'Sesi rekaman di studio'],
            ['nm_kategori' => 'Kolaborasi', 'deskripsi' => 'Proyek kolaborasi antar musisi'],
        ];

        $kategoriDokumentasi = [
            ['nm_kategori' => 'Behind The Scenes', 'deskripsi' => 'Dokumentasi proses kreatif'],
            ['nm_kategori' => 'Live Performance', 'deskripsi' => 'Dokumentasi pertunjukan langsung'],
            ['nm_kategori' => 'Music Video', 'deskripsi' => 'Video musik official'],
            ['nm_kategori' => 'Interview', 'deskripsi' => 'Wawancara dengan artis'],
            ['nm_kategori' => 'Event Coverage', 'deskripsi' => 'Liputan acara musik'],
        ];

        $now = Carbon::now();

        // Insert kategori musik
        foreach ($kategoriMusik as $kategori) {
            DB::table('kategori')->insert([
                'nm_kategori' => $kategori['nm_kategori'],
                'slug' => Str::slug($kategori['nm_kategori']),
                'deskripsi' => $kategori['deskripsi'],
                'jenis' => 'musik',
                'aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Insert kategori acara
        foreach ($kategoriAcara as $kategori) {
            DB::table('kategori')->insert([
                'nm_kategori' => $kategori['nm_kategori'],
                'slug' => Str::slug($kategori['nm_kategori']),
                'deskripsi' => $kategori['deskripsi'],
                'jenis' => 'acara',
                'aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Insert kategori dokumentasi
        foreach ($kategoriDokumentasi as $kategori) {
            DB::table('kategori')->insert([
                'nm_kategori' => $kategori['nm_kategori'],
                'slug' => Str::slug($kategori['nm_kategori']),
                'deskripsi' => $kategori['deskripsi'],
                'jenis' => 'dokumentasi',
                'aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
