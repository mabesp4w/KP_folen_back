<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KaryaMusikKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get kategori musik IDs
        $kategoriMusik = Kategori::where('jenis', 'musik')->get();
        $popId = $kategoriMusik->where('nm_kategori', 'Pop')->first()->id;
        $rockId = $kategoriMusik->where('nm_kategori', 'Rock')->first()->id;
        $jazzId = $kategoriMusik->where('nm_kategori', 'Jazz')->first()->id;
        $folkId = $kategoriMusik->where('nm_kategori', 'Folk')->first()->id;
        $electronicId = $kategoriMusik->where('nm_kategori', 'Electronic')->first()->id;
        $indieId = $kategoriMusik->where('nm_kategori', 'Indie')->first()->id;
        $hipHopId = $kategoriMusik->where('nm_kategori', 'Hip Hop')->first()->id;
        $rnbId = $kategoriMusik->where('nm_kategori', 'R&B')->first()->id;

        // Mapping karya musik dengan kategori
        $mappings = [
            1 => [$popId, $indieId], // Mimpi di Awan - Pop, Indie
            2 => [$rockId], // Petualangan Malam - Rock
            3 => [$jazzId], // Senja di Kota - Jazz
            4 => [$folkId, $indieId], // Rindu Kampung - Folk, Indie
            5 => [$electronicId], // Digital Dreams - Electronic
            6 => [$indieId, $popId], // Cahaya Pagi - Indie, Pop
            7 => [$hipHopId], // Suara Jalanan - Hip Hop
            8 => [$rnbId], // Love in Blue - R&B
            9 => [$popId], // Hujan dan Kenangan - Pop
            10 => [$rockId], // Thunder Storm - Rock
        ];

        foreach ($mappings as $karyaMusikId => $kategoriIds) {
            foreach ($kategoriIds as $kategoriId) {
                DB::table('karya_musik_kategori')->insert([
                    'karya_musik_id' => $karyaMusikId,
                    'kategori_id' => $kategoriId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
