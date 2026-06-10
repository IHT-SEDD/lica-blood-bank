<?php

namespace App\Http\Controllers;

use App\Services\Integrations\LogIntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IntegrationController extends Controller
{
    public function __construct(
        protected LogIntegrationService $logIntegrationService
    ) {}

    public function index(string $integration)
    {
        $modules = config('integrations');
        abort_unless(isset($modules[$integration]), 404);

        $view = $modules[$integration]['view'];
        abort_unless(view()->exists($view), 404);

        $formattedIntegration = Str::of($integration)
            ->replace(['-', '_'], ' ')
            ->title();
        return view($view, [
            'integration' => $formattedIntegration,
            'integrationJS' => $integration,
        ]);
    }

    public function datatable(Request $request, string $integration)
    {
        abort_unless($integration === 'log-integration', 404);
        return $this->logIntegrationService->getDatatable($request);
    }
}
