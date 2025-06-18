<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            // Kategori Musik
            ['nm_kategori' => 'Pop', 'jenis' => 'musik', 'deskripsi' => 'Musik populer mainstream'],
            ['nm_kategori' => 'Rock', 'jenis' => 'musik', 'deskripsi' => 'Musik rock dan metal'],
            ['nm_kategori' => 'Jazz', 'jenis' => 'musik', 'deskripsi' => 'Musik jazz dan blues'],
            ['nm_kategori' => 'Folk', 'jenis' => 'musik', 'deskripsi' => 'Musik folk dan akustik'],
            ['nm_kategori' => 'Electronic', 'jenis' => 'musik', 'deskripsi' => 'Musik elektronik dan EDM'],
            ['nm_kategori' => 'Indie', 'jenis' => 'musik', 'deskripsi' => 'Musik independen'],
            ['nm_kategori' => 'Hip Hop', 'jenis' => 'musik', 'deskripsi' => 'Musik hip hop dan rap'],
            ['nm_kategori' => 'R&B', 'jenis' => 'musik', 'deskripsi' => 'Rhythm and blues'],

            // Kategori Acara
            ['nm_kategori' => 'Workshop', 'jenis' => 'acara', 'deskripsi' => 'Workshop musik dan teknik'],
            ['nm_kategori' => 'Masterclass', 'jenis' => 'acara', 'deskripsi' => 'Kelas master dengan ahli'],
            ['nm_kategori' => 'Open Mic', 'jenis' => 'acara', 'deskripsi' => 'Acara open mic night'],
            ['nm_kategori' => 'Album Launch', 'jenis' => 'acara', 'deskripsi' => 'Peluncuran album'],
            ['nm_kategori' => 'Showcase', 'jenis' => 'acara', 'deskripsi' => 'Showcase artis'],
            ['nm_kategori' => 'Networking', 'jenis' => 'acara', 'deskripsi' => 'Acara networking industri musik'],

            // Kategori Dokumentasi
            ['nm_kategori' => 'Behind The Scene', 'jenis' => 'dokumentasi', 'deskripsi' => 'Dokumentasi dibalik layar'],
            ['nm_kategori' => 'Live Performance', 'jenis' => 'dokumentasi', 'deskripsi' => 'Dokumentasi pertunjukan langsung'],
            ['nm_kategori' => 'Studio Session', 'jenis' => 'dokumentasi', 'deskripsi' => 'Dokumentasi sesi studio'],
            ['nm_kategori' => 'Interview', 'jenis' => 'dokumentasi', 'deskripsi' => 'Wawancara artis'],
            ['nm_kategori' => 'Music Video', 'jenis' => 'dokumentasi', 'deskripsi' => 'Video musik'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create([
                'nm_kategori' => $kategori['nm_kategori'],
                'slug' => Str::slug($kategori['nm_kategori']),
                'jenis' => $kategori['jenis'],
                'deskripsi' => $kategori['deskripsi'],
                'aktif' => true,
            ]);
        }
    }
}
