<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class BackupLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup semua file log ke folder tujuan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logPath = storage_path('logs');
        $destination = $this->ask('Masukkan lokasi penyimpanan backup');
        $timestamp = now()->format('Ymd_His');
        $backupRoot = $destination . DIRECTORY_SEPARATOR . "LICA BLOOD BANK_{$timestamp} - LOG BACKUP";
        File::ensureDirectoryExists($backupRoot);

        $backupCount = 0;

        foreach (File::directories($logPath) as $directory) {
            $folderName = basename($directory);
            $backupFolder = $backupRoot . DIRECTORY_SEPARATOR . $folderName;
            File::ensureDirectoryExists($backupFolder);

            foreach (File::files($directory) as $file) {
                if ($file->getExtension() !== 'log') {
                    continue;
                }
                $fileName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $newFileName = $fileName . "_{$timestamp}.log";

                File::copy($file->getRealPath(), $backupFolder . DIRECTORY_SEPARATOR . $newFileName);

                $backupCount++;
            }
        }
        
        $this->info("Berhasil backup {$backupCount} file log.");
        $this->info("Lokasi backup: {$backupRoot}");
    }
}
