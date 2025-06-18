<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KategoriSeeder::class,
            KaryaMusikSeeder::class,
            JadwalKegiatanSeeder::class,
            DokumentasiSeeder::class,
            // KaryaMusikKategoriSeeder::class,
            // JadwalKategoriSeeder::class,
        ]);

        $this->command->info('🎵 Database seeded successfully with music studio data!');
        $this->command->info('📊 Data yang dibuat:');
        $this->command->info('   - 19 Kategori (8 musik, 6 acara, 5 dokumentasi)');
        $this->command->info('   - 10 Karya Musik dengan berbagai genre');
        $this->command->info('   - 10 Jadwal Kegiatan (workshop, konser, recording, dll)');
        $this->command->info('   - 10 Dokumentasi (foto & video)');
        $this->command->info('   - Relasi many-to-many antara karya musik dan kategori');
        $this->command->info('   - Relasi many-to-many antara jadwal kegiatan dan kategori');
    }
}
