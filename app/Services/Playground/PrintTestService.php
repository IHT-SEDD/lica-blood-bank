<?php

namespace App\Services\Playground;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PrintTestService
{
    // ---------- Mapping print (HTML via browser) ----------
    protected array $printMap = [
        'incompatible-letter' => 'print.blood_transfusion.incompatible-letter',
        'crossmatch-result' => 'print.blood_transfusion.crossmatch-result',
        'blood-patient-card' => 'print.blood_transfusion.blood_card_patient',
        'nota-transaction' => 'print.blood_transfusion.nota',
        'purchase-order' => 'print.history_order.po_file',
    ];

    // ---------- Mapping PDF (DomPDF download) ----------
    protected array $pdfMap = [
        'incompatible-letter' => 'pdf.blood_transfusion.incompatible-letter',
        'crossmatch-result' => 'pdf.blood_transfusion.crossmatch-result',
        'blood-patient-card' => 'pdf.blood_transfusion.blood_card_patient',
        'nota-transaction' => 'pdf.blood_transfusion.nota',
        'purchase-order' => 'pdf.history_order.po_file',
    ];

    // ---------- Fungsi print ----------
    public function print(string $print): \Illuminate\Http\Response
    {
        abort_unless(array_key_exists($print, $this->printMap), 404, "Print template [{$print}] not found.");
        $html = view($this->printMap[$print], $this->getPrintData($print))->render();
        return response($html, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    // ---------- Fungsi download file menjadi PDF ----------
    public function downloadPDF(string $print): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        abort_unless(array_key_exists($print, $this->pdfMap), 404, "PDF template [{$print}] not found.");

        $fileName = strtoupper($print) . '.pdf';
        $storagePath = 'testing/preview/pdf' . $fileName;

        $pdf = Pdf::loadView($this->pdfMap[$print], $this->getPrintData($print));
        Storage::disk('public')->put($storagePath, $pdf->output());

        return response()->download(
            Storage::disk('public')->path($storagePath),
            $fileName,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            ]
        );
    }

    // ---------- Fungsi mendapatkan jenis print ----------
    protected function getPrintData(string $print): array
    {
        return match ($print) {
            'incompatible-letter' => [
                'title'       => 'Incompatible Letter',
                'companyName' => config('app.name'),
            ],
            'crossmatch-result' => [
                'title'       => 'Crossmatch Result',
                'companyName' => config('app.name'),
            ],
            'blood-patient-card' => [
                'title'       => 'Blood Patient Card',
                'companyName' => config('app.name'),
            ],
            'nota-transaction' => [
                'title'       => 'Transaction Nota',
                'companyName' => config('app.name'),
            ],
            'purchase-order' => $this->getPurchaseOrderData(),
            default          => [],
        };
    }

    // ---------- Data dummy Purchase Order ----------
    private function getPurchaseOrderData(): array
    {
        $dummy = json_decode(
            file_get_contents(public_path('assets/data/playground/po.json'))
        );

        return [
            'title'       => 'Purchase Order',
            'companyName' => config('app.name'),
            'order'       => $dummy->order,
            'vendor'      => $dummy->vendor,
            'details'     => $dummy->details,
        ];
    }
}
