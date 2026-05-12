<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate service classes';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $input = trim($this->argument('name'));

        // Format slash yang diterima
        $input = str_replace('\\', '/', $input);

        // Contoh prefix folder:
        // Dashboard/DashboardService
        // Dashboard/Utility/DashboardService
        $segments = explode('/', $input);
        $baseName = array_pop($segments);

        // Hapus prefix service jika ada dari inputan
        $baseName = Str::replaceLast('Service', '', $baseName);

        // Tentukan lokasi penyimpanan
        $folderPath = app_path('Services');
        if (!empty($segments)) {
            $folderPath .= '/' . implode('/', $segments);
        }

        // Buat file jika belum ada
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        $namespace = 'App\\Services';
        if (!empty($segments)) {
            $namespace .= '\\' . implode('\\', $segments);
        }

        // File yang akan dihasilkan
        $files = [
            [
                'name' => "{$baseName}DataService.php",
                'content' => $this->buildDataService(
                    $namespace,
                    "{$baseName}DataService"
                ),
            ],
            [
                'name' => "{$baseName}WriteService.php",
                'content' => $this->buildWriteService(
                    $namespace,
                    "{$baseName}WriteService"
                ),
            ],
            [
                'name' => "{$baseName}AddService.php",
                'content' => $this->buildAddService(
                    $namespace,
                    "{$baseName}AddService"
                ),
            ],
        ];

        foreach ($files as $file) {
            $fullPath = $folderPath . '/' . $file['name'];
            if (File::exists($fullPath)) {
                $this->error("File already exists: {$file['name']}");
                continue;
            }

            File::put($fullPath, $file['content']);

            $this->info("Created: {$fullPath}");
        }

        return self::SUCCESS;
    }

    /**
     * Build Data Service
     */
    protected function buildDataService(string $namespace, string $className): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Http\Request;

class {$className}
{
    //
}
PHP;
    }

    /**
     * Build Write Service
     */
    protected function buildWriteService(string $namespace, string $className): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Http\Request;

class {$className}
{
    // ---------- Fungsi edit data ----------
    public function editData(Request \$request, string \$id)
    {
        //
    }
    
    // ---------- Fungsi delete data ----------
    public function deleteData(string \$id)
    {
        //
    }

    // ---------- Fungsi permanent delete data ----------
    public function permanentDeleteData(string \$id)
    {
        //
    }

    // ---------- Fungsi restore data ----------
    public function restoreData(string \$id)
    {
        //
    }
}
PHP;
    }

    /**
     * Build Add Service
     */
    protected function buildAddService(string $namespace, string $className): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Http\Request;

class {$className}
{
    // ---------- Fungsi add data ----------
    public function insertNewData(Request \$request, string \$id)
    {
        //
    }
}
PHP;
    }
}
