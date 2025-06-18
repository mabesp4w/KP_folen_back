<?php

namespace Database\Seeders;

use App\Models\KaryaMusik;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KaryaMusikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyaMusik = [
            [
                'judul' => 'Mimpi di Awan',
                'deskripsi' => 'Sebuah lagu tentang harapan dan mimpi yang tinggi',
                'nm_artis' => 'Andika Pratama',
                'genre' => 'Pop',
                'tgl_rilis' => '2024-01-15',
                'url_video' => 'https://youtube.com/watch?v=abc123',
                'url_audio' => 'https://spotify.com/track/def456',
                'thumbnail' => 'thumbnails/mimpi-di-awan.jpg',
            ],
            [
                'judul' => 'Petualangan Malam',
                'deskripsi' => 'Rock anthem tentang kebebasan malam hari',
                'nm_artis' => 'The Wanderers',
                'genre' => 'Rock',
                'tgl_rilis' => '2024-02-20',
                'url_video' => 'https://youtube.com/watch?v=xyz789',
                'url_audio' => 'https://spotify.com/track/ghi012',
                'thumbnail' => 'thumbnails/petualangan-malam.jpg',
            ],
            [
                'judul' => 'Senja di Kota',
                'deskripsi' => 'Lagu jazz yang menggambarkan suasana senja di kota besar',
                'nm_artis' => 'Maya Sari Trio',
                'genre' => 'Jazz',
                'tgl_rilis' => '2024-03-10',
                'url_video' => null,
                'url_audio' => 'https://soundcloud.com/mayasari/senja-di-kota',
                'thumbnail' => 'thumbnails/senja-di-kota.jpg',
            ],
            [
                'judul' => 'Rindu Kampung',
                'deskripsi' => 'Lagu folk akustik tentang kerinduan terhadap kampung halaman',
                'nm_artis' => 'Budi Santoso',
                'genre' => 'Folk',
                'tgl_rilis' => '2024-04-05',
                'url_video' => 'https://youtube.com/watch?v=folk123',
                'url_audio' => 'https://spotify.com/track/folk456',
                'thumbnail' => 'thumbnails/rindu-kampung.jpg',
            ],
            [
                'judul' => 'Digital Dreams',
                'deskripsi' => 'Electronic dance music dengan nuansa futuristik',
                'nm_artis' => 'DJ Elektro',
                'genre' => 'Electronic',
                'tgl_rilis' => '2024-05-12',
                'url_video' => 'https://youtube.com/watch?v=edm789',
                'url_audio' => 'https://spotify.com/track/edm012',
                'thumbnail' => 'thumbnails/digital-dreams.jpg',
            ],
            [
                'judul' => 'Cahaya Pagi',
                'deskripsi' => 'Indie pop yang menenangkan tentang harapan baru',
                'nm_artis' => 'Luna & The Stars',
                'genre' => 'Indie',
                'tgl_rilis' => '2024-06-18',
                'url_video' => 'https://youtube.com/watch?v=indie123',
                'url_audio' => 'https://spotify.com/track/indie456',
                'thumbnail' => 'thumbnails/cahaya-pagi.jpg',
            ],
            [
                'judul' => 'Suara Jalanan',
                'deskripsi' => 'Hip hop yang mengangkat realitas kehidupan urban',
                'nm_artis' => 'MC Kota',
                'genre' => 'Hip Hop',
                'tgl_rilis' => '2024-07-22',
                'url_video' => 'https://youtube.com/watch?v=hiphop789',
                'url_audio' => 'https://spotify.com/track/hiphop012',
                'thumbnail' => 'thumbnails/suara-jalanan.jpg',
            ],
            [
                'judul' => 'Love in Blue',
                'deskripsi' => 'R&B romantis dengan nuansa soul',
                'nm_artis' => 'Siska Melody',
                'genre' => 'R&B',
                'tgl_rilis' => '2024-08-14',
                'url_video' => 'https://youtube.com/watch?v=rnb123',
                'url_audio' => 'https://spotify.com/track/rnb456',
                'thumbnail' => 'thumbnails/love-in-blue.jpg',
            ],
            [
                'judul' => 'Hujan dan Kenangan',
                'deskripsi' => 'Balada pop tentang nostalgia masa lalu',
                'nm_artis' => 'Rina Poetri',
                'genre' => 'Pop',
                'tgl_rilis' => '2024-09-30',
                'url_video' => 'https://youtube.com/watch?v=balada123',
                'url_audio' => 'https://spotify.com/track/balada456',
                'thumbnail' => 'thumbnails/hujan-kenangan.jpg',
            ],
            [
                'judul' => 'Thunder Storm',
                'deskripsi' => 'Heavy metal dengan energy tinggi',
                'nm_artis' => 'Iron Warriors',
                'genre' => 'Rock',
                'tgl_rilis' => '2024-10-25',
                'url_video' => 'https://youtube.com/watch?v=metal789',
                'url_audio' => 'https://spotify.com/track/metal012',
                'thumbnail' => 'thumbnails/thunder-storm.jpg',
            ],
        ];

        foreach ($karyaMusik as $karya) {
            KaryaMusik::create($karya);
        }
    }
}
