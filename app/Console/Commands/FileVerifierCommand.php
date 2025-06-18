<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class FileVerifierCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'files:verify';

    /**
     * The console command description.
     */
    protected $description = 'Verify that all files referenced in database actually exist in storage/myStorage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verifying files in storage/myStorage...');

        $missing = [];
        $found = [];

        // Check karya musik files
        $karyaMusik = DB::table('karya_musik')->select('id', 'judul', 'url_video', 'url_audio', 'thumbnail')->get();

        foreach ($karyaMusik as $karya) {
            $files = [$karya->url_video, $karya->url_audio, $karya->thumbnail];

            foreach ($files as $file) {
                if ($file) {
                    $fullPath = base_path('storage/myStorage/' . $file);
                    if (File::exists($fullPath)) {
                        $found[] = $file;
                    } else {
                        $missing[] = [
                            'file' => $file,
                            'related_to' => "Karya Musik: {$karya->judul}",
                            'id' => $karya->id
                        ];
                    }
                }
            }
        }

        // Check dokumentasi files
        $dokumentasi = DB::table('dokumentasi')->select('id', 'judul', 'file_dokumentasi', 'thumbnail')->get();

        foreach ($dokumentasi as $doc) {
            $files = array_filter([$doc->file_dokumentasi, $doc->thumbnail]);

            foreach ($files as $file) {
                $fullPath = base_path('storage/myStorage/' . $file);
                if (File::exists($fullPath)) {
                    $found[] = $file;
                } else {
                    $missing[] = [
                        'file' => $file,
                        'related_to' => "Dokumentasi: {$doc->judul}",
                        'id' => $doc->id
                    ];
                }
            }
        }

        // Show results
        $this->info("✅ Found files: " . count($found));
        $this->error("❌ Missing files: " . count($missing));

        if (!empty($missing)) {
            $this->line('');
            $this->error('Missing files:');
            foreach ($missing as $item) {
                $this->line("  - {$item['file']} ({$item['related_to']})");
            }
        }

        // Show storage size
        $storageSize = $this->getDirectorySize(base_path('storage/myStorage'));
        $this->info("💾 Total storage size: " . $this->formatBytes($storageSize));

        // Show directory structure
        $this->line('');
        $this->info('📁 Directory structure:');
        $this->showDirectoryTree(base_path('storage/myStorage'), 'storage/myStorage');

        return empty($missing) ? 0 : 1;
    }

    /**
     * Get directory size recursively
     */
    private function getDirectorySize(string $directory): int
    {
        $size = 0;

        if (File::isDirectory($directory)) {
            foreach (File::allFiles($directory) as $file) {
                $size += $file->getSize();
            }
        }

        return $size;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Show directory tree structure
     */
    private function showDirectoryTree(string $directory, string $prefix = '', int $maxDepth = 3, int $currentDepth = 0): void
    {
        if ($currentDepth >= $maxDepth || !File::isDirectory($directory)) {
            return;
        }

        $items = collect(File::directories($directory))
            ->map(fn($dir) => ['type' => 'dir', 'path' => $dir, 'name' => basename($dir)])
            ->merge(
                collect(File::files($directory))
                    ->take(3) // Show only first 3 files per directory
                    ->map(fn($file) => ['type' => 'file', 'path' => $file->getPathname(), 'name' => $file->getFilename()])
            )
            ->sortBy('name');

        foreach ($items as $index => $item) {
            $isLast = $index === $items->count() - 1;
            $symbol = $isLast ? '└── ' : '├── ';
            $fileCount = '';

            if ($item['type'] === 'dir') {
                $count = count(File::files($item['path']));
                $fileCount = " ({$count} files)";
                $this->line("  {$prefix}{$symbol}{$item['name']}{$fileCount}");

                $newPrefix = $prefix . ($isLast ? '    ' : '│   ');
                $this->showDirectoryTree($item['path'], $newPrefix, $maxDepth, $currentDepth + 1);
            } else {
                $size = $this->formatBytes(filesize($item['path']));
                $this->line("  {$prefix}{$symbol}{$item['name']} ({$size})");
            }
        }

        // Show if there are more files
        if ($currentDepth === 0) {
            $totalFiles = count(File::allFiles($directory));
            if ($totalFiles > 0) {
                $this->line("  Total files: {$totalFiles}");
            }
        }
    }
}
