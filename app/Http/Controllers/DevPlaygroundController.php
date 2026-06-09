<?php

namespace App\Http\Controllers;

use App\Services\Playground\PrintTestService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DevPlaygroundController extends Controller
{
    public function __construct(protected PrintTestService $printTestService) {}

    public function index()
    {
        return view('pages.playground.index');
    }
    public function printTestIndex()
    {
        return view('pages.playground.print-test.index');
    }

    public function printPreview(string $print)
    {
        try {
            return $this->printTestService->print($print);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'File not found!'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to print preview File!', 'error' => $e->getMessage()], 500);
        }
    }

    public function downloadPDF(string $print)
    {
        try {
            return $this->printTestService->downloadPDF($print);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'File not found!'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to download File!', 'error' => $e->getMessage()], 500);
        }
    }
}
