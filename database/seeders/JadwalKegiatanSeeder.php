<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class JadwalKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();

        $jenisKegiatan = ['acara', 'sesi_studio', 'konser', 'rekaman'];
        $statusKegiatan = ['terjadwal', 'berlangsung', 'selesai', 'dibatalkan'];

        $judulKegiatan = [
            'Konser Akustik Senja',
            'Workshop Songwriting',
            'Open Mic Night',
            'Sesi Rekaman Album Baru',
            'Kolaborasi Lintas Genre',
            'Festival Musik Indie',
            'Pelatihan Mixing & Mastering',
            'Konser Charity',
            'Jamming Session',
            'Recording Demo',
            'Live Streaming Concert',
            'Music Production Class',
            'Band Showcase',
            'Acoustic Evening',
            'Electronic Music Workshop'
        ];

        $lokasi = [
            'Studio Musik Harmoni, Jakarta',
            'Gedung Kesenian Bandung',
            'Amphitheater ITB',
            'Balai Kartini Jakarta',
            'Studio Recording Pro',
            'Taman Budaya Yogyakarta',
            'Mall Atrium',
            'Cafe Musik Lounge',
            'Campus Center UGM',
            'Creative Hub Surabaya',
            'Music Hall Central',
            'Studio Underground',
            'Rooftop Venue',
            'Cultural Center'
        ];

        // Get kategori acara IDs
        $kategoriAcara = DB::table('kategori')->where('jenis', 'acara')->pluck('id')->toArray();

        for ($i = 0; $i < 15; $i++) {
            $jenis = $jenisKegiatan[array_rand($jenisKegiatan)];
            $waktuMulai = Carbon::instance($faker->dateTimeBetween('-1 month', '+3 months'));
            $waktuSelesai = $waktuMulai->copy()->addHours($faker->numberBetween(1, 6));

            // Determine status based on date
            $status = 'terjadwal';
            if ($waktuMulai < $now) {
                $status = $faker->randomElement(['selesai', 'dibatalkan']);
            } elseif ($waktuMulai->diffInHours($now) < 2 && $waktuMulai > $now) {
                $status = 'berlangsung';
            }

            $harga = null;
            $maksimalPeserta = null;
            $pesertaSaatIni = 0;

            // Set harga dan peserta untuk acara dan konser
            if (in_array($jenis, ['acara', 'konser'])) {
                $harga = $faker->randomElement([0, 25000, 50000, 75000, 100000, 150000]);
                $maksimalPeserta = $faker->numberBetween(20, 200);
                $pesertaSaatIni = $faker->numberBetween(0, $maksimalPeserta);
            }

            $jadwalId = DB::table('jadwal_kegiatan')->insertGetId([
                'judul' => $judulKegiatan[$i] ?? $faker->sentence(3),
                'deskripsi' => $faker->paragraph(2),
                'jenis' => $jenis,
                'waktu_mulai' => $waktuMulai->format('Y-m-d H:i:s'),
                'waktu_selesai' => $waktuSelesai->format('Y-m-d H:i:s'),
                'lokasi' => $lokasi[array_rand($lokasi)],
                'harga' => $harga,
                'maksimal_peserta' => $maksimalPeserta,
                'peserta_saat_ini' => $pesertaSaatIni,
                'status' => $status,
                'catatan' => $faker->optional(0.7)->sentence(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Assign random categories (1-2 categories per jadwal)
            $randomKategori = $faker->randomElements($kategoriAcara, $faker->numberBetween(1, 2));
            foreach ($randomKategori as $kategoriId) {
                DB::table('jadwal_kategori')->insert([
                    'jadwal_kegiatan_id' => $jadwalId,
                    'kategori_id' => $kategoriId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
