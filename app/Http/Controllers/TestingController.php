<?php

namespace App\Http\Controllers;

use App\Services\TestingDataService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    public function __construct(protected TestingDataService $testingDataService) {}

    public function index()
    {
        return view('pages.testing.index');
    }

    public function printPreview(string $print)
    {
        try {
            return $this->testingDataService->resolvePrint($print);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'File not found!'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to print preview File!'], 500);
        }
    }
}
