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
        $destination = $this->ask('Masukkan lokasi backup');

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $files = File::files($logPath);

        $backupCount = 0;

        foreach ($files as $file) {
            if ($file->getExtension() === 'log') {
                $fileName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $newFileName = $fileName . '_' . Carbon::now()->format('Ymd_His') . '.log';
                File::copy(
                    $file->getRealPath(),
                    $destination . DIRECTORY_SEPARATOR . $newFileName
                );
                $backupCount++;
            }
        }

        $this->info("Berhasil backup {$backupCount} file log.");
    }
}
