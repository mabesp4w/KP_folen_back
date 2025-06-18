<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DokumentasiSeeder extends Seeder
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

        $judulDokumentasi = [
            'Behind The Scenes Recording',
            'Live Performance Highlights',
            'Music Video Production',
            'Interview Session',
            'Concert Moments',
            'Studio Sessions',
            'Rehearsal Documentation',
            'Fan Interactions',
            'Equipment Setup',
            'Creative Process',
            'Backstage Stories',
            'Performance Energy',
            'Artist Portrait',
            'Event Coverage'
        ];

        $lokasi = [
            'Studio Musik Jakarta',
            'Panggung Utama',
            'Backstage Area',
            'Recording Room',
            'Concert Hall',
            'Outdoor Stage',
            'Rehearsal Space',
            'Green Room',
            'Main Venue',
            'Sound Check Area'
        ];

        // Get IDs untuk polymorphic relationship
        $karyaMusikIds = DB::table('karya_musik')->pluck('id')->toArray();
        $jadwalKegiatanIds = DB::table('jadwal_kegiatan')->pluck('id')->toArray();

        $filesCreated = 0;

        for ($i = 0; $i < 25; $i++) {
            $jenis = $faker->randomElement(['foto', 'video']);
            $tglDokumentasi = $faker->dateTimeBetween('-1 year', 'now');

            // Random polymorphic relationship
            $terdokumentasiType = null;
            $terdokumentasiId = null;

            if ($faker->boolean(70)) { // 70% chance memiliki relasi
                if ($faker->boolean()) {
                    $terdokumentasiType = 'App\\Models\\KaryaMusik';
                    $terdokumentasiId = $faker->randomElement($karyaMusikIds);
                } else {
                    $terdokumentasiType = 'App\\Models\\JadwalKegiatan';
                    $terdokumentasiId = $faker->randomElement($jadwalKegiatanIds);
                }
            }

            $fileExtension = $jenis === 'foto' ? 'jpg' : 'mp4';
            $folderName = $jenis === 'foto' ? 'photos' : 'videos';

            // Buat file path
            $filePath = "dokumentasi/{$folderName}/doc_" . ($i + 1) . '.' . $fileExtension;
            $thumbnailPath = $jenis === 'video' ? "dokumentasi/thumbnails/thumb_" . ($i + 1) . '.jpg' : null;

            // Buat file fisik
            $this->createDummyFile($filePath, $jenis);
            $filesCreated++;

            if ($thumbnailPath) {
                $this->createDummyFile($thumbnailPath, 'image');
                $filesCreated++;
            }

            DB::table('dokumentasi')->insert([
                'judul' => $judulDokumentasi[array_rand($judulDokumentasi)] . ' ' . ($i + 1),
                'deskripsi' => $faker->paragraph(2),
                'jenis' => $jenis,
                'file_dokumentasi' => $filePath,
                'url_embed' => $jenis === 'video' ? $faker->optional(0.3)->url() : null,
                'thumbnail' => $thumbnailPath,
                'tgl_dokumentasi' => $tglDokumentasi->format('Y-m-d'),
                'lokasi' => $lokasi[array_rand($lokasi)],
                'terdokumentasi_type' => $terdokumentasiType,
                'terdokumentasi_id' => $terdokumentasiId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info("✅ Dokumentasi seeder completed with {$filesCreated} files created!");
    }

    /**
     * Create necessary directories
     */
    private function createDirectories(): void
    {
        $directories = [
            'storage/myStorage/dokumentasi/photos',
            'storage/myStorage/dokumentasi/videos',
            'storage/myStorage/dokumentasi/thumbnails',
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
            case 'foto':
            case 'image':
                $content = $this->downloadImageFromAPI($relativePath);
                break;
            default:
                $content = "Dummy file content for " . basename($relativePath);
        }

        File::put($fullPath, $content);
    }

    /**
     * Download attractive image from API with appealing categories
     */
    private function downloadImageFromAPI(string $relativePath): string
    {
        try {
            // Extract number from filename for seed consistency
            preg_match('/(\d+)\.jpg$/', $relativePath, $matches);
            $seed = $matches[1] ?? rand(1, 1000);

            // Eye-catching documentation categories
            if (strpos($relativePath, 'photos') !== false) {
                // Documentation photos - dynamic and colorful
                $docCategories = [
                    'concert-stage-lights',
                    'music-festival-crowd',
                    'dj-performance-neon',
                    'band-silhouette-sunset',
                    'recording-studio-glow',
                    'musician-spotlight',
                    'concert-hands-up',
                    'stage-smoke-lights',
                    'festival-fireworks',
                    'backstage-preparation',
                    'sound-engineer-work',
                    'crowd-energy-night',
                    'artist-microphone-close',
                    'guitar-strings-macro',
                    'drums-action-shot',
                    'audience-cheering-colors',
                    'live-music-atmosphere',
                    'concert-photography',
                    'music-equipment-artistic',
                    'performer-silhouette'
                ];
            } else {
                // Video thumbnails - more dramatic and cinematic
                $docCategories = [
                    'concert-epic-wide',
                    'stage-dramatic-lighting',
                    'festival-aerial-view',
                    'musician-performance-art',
                    'crowd-ocean-lights',
                    'band-action-dynamic',
                    'studio-cinematic-mood',
                    'concert-pyrotechnics',
                    'festival-sunset-crowd',
                    'performer-energy-burst',
                    'stage-production-massive',
                    'music-video-style',
                    'concert-atmosphere-epic',
                    'live-show-spectacular',
                    'artist-portrait-dramatic',
                    'festival-night-aerial',
                    'stage-effects-stunning',
                    'music-documentary-style',
                    'concert-crowd-sea',
                    'performance-art-visual'
                ];
            }

            $category = $docCategories[($seed - 1) % count($docCategories)];

            // Ultra high resolution for stunning visuals
            if (strpos($relativePath, 'thumbnail') !== false) {
                $width = 800;
                $height = 450; // 16:9 aspect ratio
            } else {
                $width = 2560;
                $height = 1440; // 2K resolution
            }

            // Multiple APIs with fallbacks for best results
            $apis = [
                // Unsplash with music/concert focus
                "https://source.unsplash.com/{$width}x{$height}/?" . urlencode($category) . "&sig={$seed}",
                // Alternative Unsplash query
                "https://source.unsplash.com/{$width}x{$height}/?music,concert,festival&sig={$seed}",
                // Picsum with artistic blur
                "https://picsum.photos/{$width}/{$height}?random={$seed}&blur=2",
                // Custom placeholder with attractive colors
                "https://via.placeholder.com/{$width}x{$height}/8E44AD/FFFFFF?text=" . urlencode("LIVE {$seed}")
            ];

            foreach ($apis as $index => $url) {
                try {
                    $this->command->info("🎬 Downloading stunning documentation image: {$category} ({$width}x{$height}) - API " . ($index + 1));

                    $context = stream_context_create([
                        'http' => [
                            'timeout' => 50,
                            'user_agent' => 'Music Documentation Studio/2.0',
                            'follow_location' => true,
                            'max_redirects' => 5
                        ]
                    ]);

                    $imageContent = file_get_contents($url, false, $context);

                    if ($imageContent && strlen($imageContent) > 10000) {
                        $this->command->info("✨ Successfully downloaded stunning image from API " . ($index + 1));
                        return $imageContent;
                    }
                } catch (\Exception $e) {
                    $this->command->warn("⚠️  API " . ($index + 1) . " failed: " . $e->getMessage());
                    continue;
                }
            }

            throw new \Exception("All APIs failed");
        } catch (\Exception $e) {
            $this->command->warn("🎨 Creating stunning custom documentation image...");
            return $this->createStunningCustomImage($relativePath);
        }
    }

    /**
     * Create stunning custom documentation image with cinematic effects
     */
    private function createStunningCustomImage(string $relativePath): string
    {
        if (!extension_loaded('gd')) {
            return $this->getDummyImageContent();
        }

        // Extract number for consistent styling
        preg_match('/(\d+)\.jpg$/', $relativePath, $matches);
        $seed = (int)($matches[1] ?? 1);

        $width = strpos($relativePath, 'thumbnail') !== false ? 800 : 2560;
        $height = strpos($relativePath, 'thumbnail') !== false ? 450 : 1440;

        $image = imagecreatetruecolor($width, $height);

        // Cinematic color schemes for documentation
        $colorSchemes = [
            // Deep Purple-Blue (concert vibes)
            ['start' => [75, 0, 130], 'middle' => [138, 43, 226], 'end' => [0, 191, 255]],
            // Warm Orange-Red (festival sunset)
            ['start' => [255, 69, 0], 'middle' => [255, 140, 0], 'end' => [255, 215, 0]],
            // Cool Teal-Purple (electronic music)
            ['start' => [0, 128, 128], 'middle' => [64, 224, 208], 'end' => [138, 43, 226]],
            // Dramatic Red-Black (rock concert)
            ['start' => [139, 0, 0], 'middle' => [220, 20, 60], 'end' => [255, 69, 0]],
            // Neon Green-Blue (DJ set)
            ['start' => [0, 255, 127], 'middle' => [0, 250, 154], 'end' => [0, 191, 255]],
            // Royal Purple-Gold (luxury event)
            ['start' => [75, 0, 130], 'middle' => [148, 0, 211], 'end' => [255, 215, 0]]
        ];

        $scheme = $colorSchemes[$seed % count($colorSchemes)];

        // Create multi-stop gradient background
        for ($y = 0; $y < $height; $y++) {
            $ratio = $y / $height;

            if ($ratio < 0.5) {
                $localRatio = $ratio * 2;
                $r = (int)($scheme['start'][0] * (1 - $localRatio) + $scheme['middle'][0] * $localRatio);
                $g = (int)($scheme['start'][1] * (1 - $localRatio) + $scheme['middle'][1] * $localRatio);
                $b = (int)($scheme['start'][2] * (1 - $localRatio) + $scheme['middle'][2] * $localRatio);
            } else {
                $localRatio = ($ratio - 0.5) * 2;
                $r = (int)($scheme['middle'][0] * (1 - $localRatio) + $scheme['end'][0] * $localRatio);
                $g = (int)($scheme['middle'][1] * (1 - $localRatio) + $scheme['end'][1] * $localRatio);
                $b = (int)($scheme['middle'][2] * (1 - $localRatio) + $scheme['end'][2] * $localRatio);
            }

            $color = imagecolorallocate($image, $r, $g, $b);
            imageline($image, 0, $y, $width, $y, $color);
        }

        // Add cinematic overlay effects
        $white = imagecolorallocate($image, 255, 255, 255);
        $overlay = imagecolorallocatealpha($image, 255, 255, 255, 80);
        $glowOverlay = imagecolorallocatealpha($image, 255, 255, 255, 120);

        $centerX = $width / 2;
        $centerY = $height / 2;

        // Add dynamic visual elements based on seed
        switch ($seed % 4) {
            case 0:
                // Stage lighting effect
                for ($i = 0; $i < 6; $i++) {
                    $x = ($width / 6) * $i + ($width / 12);
                    imagefilledellipse($image, $x, $height * 0.2, 60, 120, $glowOverlay);
                }
                break;

            case 1:
                // Sound wave visualization
                for ($i = 0; $i < 10; $i++) {
                    $x = ($width / 10) * $i;
                    $waveHeight = 50 + sin($i * 0.8) * 100;
                    imagefilledrectangle($image, $x, $centerY - $waveHeight, $x + 20, $centerY + $waveHeight, $overlay);
                }
                break;

            case 2:
                // Spotlight circles
                imagefilledellipse($image, $centerX, $centerY, 300, 300, $glowOverlay);
                imagefilledellipse($image, $centerX - 200, $centerY + 100, 150, 150, $overlay);
                imagefilledellipse($image, $centerX + 200, $centerY - 100, 150, 150, $overlay);
                break;

            case 3:
                // Concert crowd silhouette effect
                for ($i = 0; $i < 20; $i++) {
                    $x = ($width / 20) * $i;
                    $crowdHeight = 100 + rand(0, 100);
                    imagefilledrectangle($image, $x, $height - $crowdHeight, $x + 25, $height, $overlay);
                }
                break;
        }

        // Add stylized text with glow effect
        $font = 5; // Built-in large font
        $text1 = "LIVE";
        $text2 = "DOCUMENTATION";
        $text3 = "#" . str_pad($seed, 3, '0', STR_PAD_LEFT);

        // Calculate text positions
        $text1Width = imagefontwidth($font) * strlen($text1);
        $text2Width = imagefontwidth($font) * strlen($text2);
        $text3Width = imagefontwidth($font) * strlen($text3);

        $x1 = ($width - $text1Width) / 2;
        $x2 = ($width - $text2Width) / 2;
        $x3 = ($width - $text3Width) / 2;
        $y1 = $centerY - 40;
        $y2 = $y1 + 40;
        $y3 = $y2 + 40;

        // Add glow effect with multiple shadow layers
        $shadowColor = imagecolorallocate($image, 0, 0, 0);
        for ($offset = 3; $offset > 0; $offset--) {
            imagestring($image, $font, $x1 + $offset, $y1 + $offset, $text1, $shadowColor);
            imagestring($image, $font, $x2 + $offset, $y2 + $offset, $text2, $shadowColor);
            imagestring($image, $font, $x3 + $offset, $y3 + $offset, $text3, $shadowColor);
        }

        // Add main text
        imagestring($image, $font, $x1, $y1, $text1, $white);
        imagestring($image, $font, $x2, $y2, $text2, $white);
        imagestring($image, $font, $x3, $y3, $text3, $white);

        // Capture output with maximum quality
        ob_start();
        imagejpeg($image, null, 98); // Ultra high quality
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
        $width = 600;
        $height = 400;

        $image = imagecreate($width, $height);

        // Different colors for documentation
        $colors = [
            [33, 150, 243],   // Blue
            [76, 175, 80],    // Green
            [255, 87, 34],    // Deep Orange
            [156, 39, 176],   // Purple
            [0, 188, 212],    // Cyan
            [255, 193, 7],    // Amber
        ];

        $colorIndex = rand(0, count($colors) - 1);
        $color = $colors[$colorIndex];

        $bgColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);
        $textColor = imagecolorallocate($image, 255, 255, 255);

        imagefill($image, 0, 0, $bgColor);

        // Add text
        $text = "DOCUMENTATION\nPLACEHOLDER";
        $lines = explode("\n", $text);

        $y = $height / 2 - (count($lines) * 10);
        foreach ($lines as $line) {
            $textWidth = imagefontwidth(4) * strlen($line);
            $x = ($width - $textWidth) / 2;
            imagestring($image, 4, $x, $y, $line, $textColor);
            $y += 25;
        }

        // Capture output
        ob_start();
        imagejpeg($image, null, 85);
        $imageContent = ob_get_clean();

        imagedestroy($image);

        return $imageContent;
    }
}
