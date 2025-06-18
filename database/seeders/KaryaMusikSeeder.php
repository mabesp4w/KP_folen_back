<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Faker\Factory as Faker;
use Carbon\Carbon;

class KaryaMusikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();

        // Buat folder jika belum ada
        $this->createDirectories();

        $judulLagu = [
            'Melodi Senja',
            'Rindu Dalam Hujan',
            'Cahaya Fajar',
            'Mimpi Yang Terlupa',
            'Langit Biru',
            'Cerita Kita',
            'Waktu Yang Hilang',
            'Jejak Langkah',
            'Bintang Di Malam',
            'Suara Hati',
            'Perjalanan Cinta',
            'Bayang Masa Lalu',
            'Harapan Baru',
            'Senyum Terakhir',
            'Kenangan Indah',
            'Lagu Untuk Dunia',
            'Mentari Pagi',
            'Gelombang Kasih',
            'Harmoni Jiwa',
            'Impian Sejati'
        ];

        $artis = [
            'Andi Prasetyo',
            'Sari Melati',
            'Budi Santoso',
            'Maya Sari',
            'Rizki Ramadhan',
            'Dewi Lestari',
            'Fajar Nugraha',
            'Indira Putri',
            'Doni Setiawan',
            'Ratna Wulan',
            'Hafiz Rahman',
            'Lina Marlina',
            'Yoga Pratama',
            'Sinta Dewi',
            'Arief Wijaya',
            'Nadia Kusuma'
        ];

        $genres = ['Pop', 'Rock', 'Jazz', 'Blues', 'Folk', 'Electronic'];

        // Get kategori musik IDs
        $kategoriMusik = DB::table('kategori')->where('jenis', 'musik')->pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            $tglRilis = $faker->dateTimeBetween('-2 years', 'now');

            // Buat file fisik
            $videoPath = "videos/music/" . ($i + 1) . ".mp4";
            $audioPath = "audio/music/" . ($i + 1) . ".mp3";
            $thumbnailPath = "thumbnails/music/" . ($i + 1) . ".jpg";

            $this->createDummyFile($videoPath, 'video');
            $this->createDummyFile($audioPath, 'audio');
            $this->createDummyFile($thumbnailPath, 'image');

            $karyaMusikId = DB::table('karya_musik')->insertGetId([
                'judul' => $judulLagu[$i] ?? $faker->sentence(2),
                'deskripsi' => $faker->paragraph(3),
                'nm_artis' => $artis[array_rand($artis)],
                'genre' => $genres[array_rand($genres)],
                'tgl_rilis' => $tglRilis->format('Y-m-d'),
                'url_video' => $videoPath,
                'url_audio' => $audioPath,
                'thumbnail' => $thumbnailPath,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Assign random categories (1-3 categories per karya musik)
            $randomKategori = $faker->randomElements($kategoriMusik, $faker->numberBetween(1, 3));
            foreach ($randomKategori as $kategoriId) {
                DB::table('karya_musik_kategori')->insert([
                    'karya_musik_id' => $karyaMusikId,
                    'kategori_id' => $kategoriId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->command->info('✅ Karya musik seeder completed with ' . (20 * 3) . ' files created!');
    }

    /**
     * Create necessary directories
     */
    private function createDirectories(): void
    {
        $directories = [
            'storage/myStorage/videos/music',
            'storage/myStorage/audio/music',
            'storage/myStorage/thumbnails/music',
        ];

        foreach ($directories as $dir) {
            $fullPath = base_path($dir);
            if (!File::exists($fullPath)) {
                File::makeDirectory($fullPath, 0755, true);
                $this->command->info("📁 Created directory: {$dir}");
            }
        }
    }

    /**
     * Create dummy file with appropriate content
     */
    private function createDummyFile(string $relativePath, string $type): void
    {
        $fullPath = base_path('storage/myStorage/' . $relativePath);

        if (File::exists($fullPath)) {
            return; // File already exists
        }

        switch ($type) {
            case 'video':
                $content = $this->getDummyVideoContent();
                break;
            case 'audio':
                $content = $this->getDummyAudioContent();
                break;
            case 'image':
                $content = $this->downloadImageFromAPI($relativePath);
                break;
            default:
                $content = "Dummy file content for " . basename($relativePath);
        }

        File::put($fullPath, $content);
    }

    /**
     * Download image from API with appealing categories
     */
    private function downloadImageFromAPI(string $relativePath): string
    {
        try {
            // Extract number from filename for seed consistency
            preg_match('/(\d+)\.jpg$/', $relativePath, $matches);
            $seed = $matches[1] ?? rand(1, 1000);

            // More attractive and eye-catching music categories
            $musicCategories = [
                'electric-guitar-neon',
                'dj-party-lights',
                'concert-crowd-energy',
                'recording-studio-professional',
                'music-festival-sunset',
                'vinyl-records-vintage',
                'synthesizer-colorful',
                'drummer-action-shot',
                'singer-spotlight',
                'piano-keys-close-up',
                'headphones-music',
                'music-notes-abstract',
                'band-performance-stage',
                'audio-mixing-board',
                'microphone-vintage',
                'music-equalizer-sound',
                'guitar-amplifier',
                'live-concert-energy',
                'music-studio-dark',
                'sound-waves-visual'
            ];

            $category = $musicCategories[($seed - 1) % count($musicCategories)];

            // Higher resolution for more attractive images
            if (strpos($relativePath, 'thumbnail') !== false) {
                $width = 600;
                $height = 400;
            } else {
                $width = 1920;
                $height = 1080;
            }

            // Try multiple APIs for better results
            $apis = [
                // Unsplash with specific music terms (highest quality)
                "https://source.unsplash.com/{$width}x{$height}/?" . urlencode($category) . "&sig={$seed}",
                // Picsum with blur effect for artistic look
                "https://picsum.photos/{$width}/{$height}?random={$seed}&blur=1",
                // Placeholder.com with custom colors
                "https://via.placeholder.com/{$width}x{$height}/FF6B6B/FFFFFF?text=" . urlencode("MUSIC {$seed}")
            ];

            foreach ($apis as $index => $url) {
                try {
                    $this->command->info("🎵 Downloading attractive music image: {$category} ({$width}x{$height}) - API " . ($index + 1));

                    $context = stream_context_create([
                        'http' => [
                            'timeout' => 45,
                            'user_agent' => 'Music Studio App/2.0',
                            'follow_location' => true,
                            'max_redirects' => 3
                        ]
                    ]);

                    $imageContent = file_get_contents($url, false, $context);

                    if ($imageContent && strlen($imageContent) > 5000) {
                        $this->command->info("✅ Successfully downloaded from API " . ($index + 1));
                        return $imageContent;
                    }
                } catch (\Exception $e) {
                    $this->command->warn("⚠️  API " . ($index + 1) . " failed: " . $e->getMessage());
                    continue;
                }
            }

            throw new \Exception("All APIs failed");
        } catch (\Exception $e) {
            $this->command->warn("🎨 Creating attractive custom placeholder...");
            return $this->createAttractiveCustomImage($relativePath);
        }
    }

    /**
     * Create attractive custom image with gradients and effects
     */
    private function createAttractiveCustomImage(string $relativePath): string
    {
        if (!extension_loaded('gd')) {
            return $this->getDummyImageContent();
        }

        // Extract number for consistent styling
        preg_match('/(\d+)\.jpg$/', $relativePath, $matches);
        $seed = (int)($matches[1] ?? 1);

        $width = strpos($relativePath, 'thumbnail') !== false ? 600 : 1920;
        $height = strpos($relativePath, 'thumbnail') !== false ? 400 : 1080;

        $image = imagecreatetruecolor($width, $height);

        // Attractive gradient color schemes
        $colorSchemes = [
            // Purple-Pink gradient
            ['start' => [138, 43, 226], 'end' => [255, 20, 147]],
            // Blue-Cyan gradient
            ['start' => [0, 191, 255], 'end' => [30, 144, 255]],
            // Orange-Red gradient
            ['start' => [255, 140, 0], 'end' => [255, 69, 0]],
            // Green-Teal gradient
            ['start' => [0, 255, 127], 'end' => [64, 224, 208]],
            // Gold-Orange gradient
            ['start' => [255, 215, 0], 'end' => [255, 165, 0]],
            // Magenta-Purple gradient
            ['start' => [199, 21, 133], 'end' => [148, 0, 211]]
        ];

        $scheme = $colorSchemes[$seed % count($colorSchemes)];

        // Create gradient background
        for ($y = 0; $y < $height; $y++) {
            $ratio = $y / $height;
            $r = (int)($scheme['start'][0] * (1 - $ratio) + $scheme['end'][0] * $ratio);
            $g = (int)($scheme['start'][1] * (1 - $ratio) + $scheme['end'][1] * $ratio);
            $b = (int)($scheme['start'][2] * (1 - $ratio) + $scheme['end'][2] * $ratio);

            $color = imagecolorallocate($image, $r, $g, $b);
            imageline($image, 0, $y, $width, $y, $color);
        }

        // Add attractive overlay elements
        $white = imagecolorallocate($image, 255, 255, 255);
        $overlay = imagecolorallocatealpha($image, 255, 255, 255, 100);

        // Add music-themed shapes
        $centerX = $width / 2;
        $centerY = $height / 2;

        // Draw music note or waveform pattern
        if ($seed % 2 === 0) {
            // Draw stylized music note
            imagefilledellipse($image, $centerX - 50, $centerY, 80, 80, $overlay);
            imagefilledrectangle($image, $centerX + 20, $centerY - 100, $centerX + 30, $centerY, $overlay);
        } else {
            // Draw sound wave pattern
            for ($i = 0; $i < 5; $i++) {
                $x = $centerX - 100 + ($i * 50);
                $waveHeight = 50 + ($i * 20);
                imagefilledrectangle($image, $x, $centerY - $waveHeight, $x + 20, $centerY + $waveHeight, $overlay);
            }
        }

        // Add stylized text
        $font = 5; // Built-in large font
        $text1 = "MUSIC";
        $text2 = "STUDIO";

        // Calculate text position for centering
        $text1Width = imagefontwidth($font) * strlen($text1);
        $text2Width = imagefontwidth($font) * strlen($text2);

        $x1 = ($width - $text1Width) / 2;
        $x2 = ($width - $text2Width) / 2;
        $y1 = $centerY + 100;
        $y2 = $y1 + 30;

        // Add text shadow effect
        imagestring($image, $font, $x1 + 2, $y1 + 2, $text1, imagecolorallocate($image, 0, 0, 0));
        imagestring($image, $font, $x2 + 2, $y2 + 2, $text2, imagecolorallocate($image, 0, 0, 0));

        // Add main text
        imagestring($image, $font, $x1, $y1, $text1, $white);
        imagestring($image, $font, $x2, $y2, $text2, $white);

        // Capture output
        ob_start();
        imagejpeg($image, null, 95); // High quality
        $imageContent = ob_get_clean();

        imagedestroy($image);

        return $imageContent;
    }

    /**
     * Get dummy video content (minimal MP4 header)
     */
    private function getDummyVideoContent(): string
    {
        return base64_decode('AAAAIGZ0eXBpc29tAAACAGlzb21pc28yYXZjMW1wNDEAAAAIZnJlZQAAAs1tZGF0');
    }

    /**
     * Get dummy audio content (minimal MP3 header)
     */
    private function getDummyAudioContent(): string
    {
        return base64_decode('SUQzAwAAAAAeBFRJVDIAAAACAABURUlUIFRpdGxlAA==');
    }

    /**
     * Get dummy image content (colorful placeholder if API fails)
     */
    private function getDummyImageContent(): string
    {
        // Create a simple colored square placeholder using PHP GD if available
        if (extension_loaded('gd')) {
            return $this->createColoredPlaceholder();
        }

        // Fallback to minimal JPEG header
        return base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/AqoA');
    }

    /**
     * Create a colored placeholder image using GD
     */
    private function createColoredPlaceholder(): string
    {
        $width = 400;
        $height = 300;

        $image = imagecreate($width, $height);

        // Random colors for variety
        $colors = [
            [255, 99, 132],   // Pink
            [54, 162, 235],   // Blue
            [255, 205, 86],   // Yellow
            [75, 192, 192],   // Teal
            [153, 102, 255],  // Purple
            [255, 159, 64],   // Orange
        ];

        $colorIndex = rand(0, count($colors) - 1);
        $color = $colors[$colorIndex];

        $bgColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);
        $textColor = imagecolorallocate($image, 255, 255, 255);

        imagefill($image, 0, 0, $bgColor);

        // Add text
        $text = "MUSIC\nPLACEHOLDER";
        $lines = explode("\n", $text);

        $y = $height / 2 - (count($lines) * 10);
        foreach ($lines as $line) {
            $textWidth = imagefontwidth(3) * strlen($line);
            $x = ($width - $textWidth) / 2;
            imagestring($image, 3, $x, $y, $line, $textColor);
            $y += 20;
        }

        // Capture output
        ob_start();
        imagejpeg($image, null, 80);
        $imageContent = ob_get_clean();

        imagedestroy($image);

        return $imageContent;
    }
}
