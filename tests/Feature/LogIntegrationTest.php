<?php

namespace Tests\Feature;

use App\Models\LogIntegration;
use App\Models\User;
use App\Services\Integrations\LogIntegrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed standard roles/setup if any, or just create a user
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    public function test_log_integration_page_requires_auth(): void
    {
        $response = $this->get('/integration/log-integration');
        $response->assertRedirect('/login');
    }

    public function test_log_integration_page_can_be_rendered_when_authenticated(): void
    {
        $user = User::factory()->create();
        // Spatie roles if required. Let's make sure the user has access.
        // Wait, does integration page have role checking? 
        // Let's check routes: Route::prefix('integration') is under auth middleware.
        $response = $this->actingAs($user)->get('/integration/log-integration');
        
        $response->assertStatus(200);
        $response->assertSee('List Data of Receive data');
        $response->assertSee('List Data of Send Result');
    }

    public function test_log_integration_datatable_queries_correctly(): void
    {
        $user = User::factory()->create();

        // Create mock integration records
        LogIntegration::create([
            'public_id' => '12345678-1234-1234-1234-123456789012',
            'order_number' => 'ORD-1001',
            'endpoint' => 'http://localhost/test',
            'payload' => ['foo' => 'bar'],
            'message' => 'Test success',
            'status' => 'success',
            'type' => 'new_request',
        ]);

        LogIntegration::create([
            'public_id' => '87654321-1234-1234-1234-123456789012',
            'order_number' => 'ORD-1002',
            'endpoint' => 'http://localhost/send',
            'payload' => ['baz' => 'qux'],
            'message' => 'Send success',
            'status' => 'success',
            'type' => 'send_result',
        ]);

        // Query new_request
        $response = $this->actingAs($user)->getJson('/integration/log-integration/data?type=new_request');
        $response->assertStatus(200);
        $response->assertJsonFragment(['order_number' => 'ORD-1001']);
        $response->assertJsonMissing(['order_number' => 'ORD-1002']);

        // Query send_result
        $response = $this->actingAs($user)->getJson('/integration/log-integration/data?type=send_result');
        $response->assertStatus(200);
        $response->assertJsonFragment(['order_number' => 'ORD-1002']);
        $response->assertJsonMissing(['order_number' => 'ORD-1001']);
    }

    public function test_service_inserts_data_correctly(): void
    {
        $service = app(LogIntegrationService::class);
        
        $payload = [
            'transaksi' => [
                'no_order' => 'ORD-9999'
            ],
            'info' => 'testing'
        ];

        $service->insertData('new_request', 'success', 'Sample integration log', $payload, 'http://test-endpoint');

        $this->assertDatabaseHas('log_integrations', [
            'order_number' => 'ORD-9999',
            'status' => 'success',
            'type' => 'new_request',
            'message' => 'Sample integration log',
            'endpoint' => 'http://test-endpoint',
        ]);

        $log = LogIntegration::where('order_number', 'ORD-9999')->first();
        $this->assertEquals($payload, $log->payload);
    }
}
