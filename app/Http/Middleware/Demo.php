<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Demo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Deteksi akses via port demo (8099)
        if ($request->getPort() == 8099) {
            $this->loadDemoEnv();
        }

        return $next($request);
    }

    protected function loadDemoEnv(): void
    {
        $demoEnvPath = base_path('.env.demo');

        if (!file_exists($demoEnvPath)) {
            return;
        }

        // Hanya load jika belum di-load sebelumnya
        if (app()->environment('demo')) {
            return;
        }

        $lines = file($demoEnvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Skip komentar
            if (str_starts_with(trim($line), '#')) continue;

            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value, " \t\n\r\0\x0B\"'");

                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }

        // Override config session secara langsung setelah env di-load
        config([
            'session.cookie' => env('SESSION_COOKIE', 'lica_blood_bank_demo_session'),
        ]);

        // Set app environment ke 'demo'
        app()->detectEnvironment(fn() => 'demo');
    }
}
