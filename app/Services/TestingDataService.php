<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class TestingDataService
{
    protected array $printMap = [
        'incompatible-letter' => 'prints.blood_transfusion.incompatible-letter',
    ];

    public function resolveprint(string $print): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if (! array_key_exists($print, $this->printMap)) {
            abort(404, "Print template [{$print}] not found.");
        }

        $view     = $this->printMap[$print];
        $data     = $this->getPrintData($print);
        $fileName = strtoupper($print) . '.pdf';
        $storagePath = 'testing/preview/' . $fileName;

        $pdf = Pdf::loadView($view, $data);
        Storage::disk('public')->put($storagePath, $pdf->output());

        $absolutePath = Storage::disk('public')->path($storagePath);

        return response()->download($absolutePath, $fileName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    protected function getPrintData(string $print): array
    {
        return match ($print) {
            'incompatible-letter' => [
                'title' => 'Incompatible Letter',
                'companyName' => config('app.name'),
            ],
            default => [],
        };
    }
}
