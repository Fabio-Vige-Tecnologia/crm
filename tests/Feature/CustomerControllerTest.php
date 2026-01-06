<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_requires_authentication_to_access_customers(): void
    {
        $response = $this->getJson('/api/v1/customers');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_can_list_customers_with_pagination(): void
    {
        Sanctum::actingAs($this->user);

        Customer::factory()->count(20)->create();

        $response = $this->getJson('/api/v1/customers');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'first_name',
                        'last_name',
                        'full_name',
                        'email',
                        'phone',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'links',
                'meta',
            ])
            ->assertJsonCount(15, 'data');
    }

    /** @test */
    public function it_can_create_a_customer(): void
    {
        Sanctum::actingAs($this->user);

        $customerData = [
            'first_name' => 'João',
            'last_name' => 'Silva',
            'email' => 'joao@example.com',
            'phone' => '11999999999',
        ];

        $response = $this->postJson('/api/v1/customers', $customerData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'full_name',
                    'email',
                    'phone',
                ]
            ])
            ->assertJson([
                'data' => [
                    'first_name' => 'João',
                    'last_name' => 'Silva',
                    'email' => 'joao@example.com',
                ]
            ]);

        $this->assertDatabaseHas('customers', [
            'email' => 'joao@example.com',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_customer(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/customers', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['first_name', 'last_name', 'email']);
    }

    /** @test */
    public function it_validates_email_uniqueness_when_creating_customer(): void
    {
        Sanctum::actingAs($this->user);

        $existingCustomer = Customer::factory()->create([
            'email' => 'existing@example.com'
        ]);

        $response = $this->postJson('/api/v1/customers', [
            'first_name' => 'João',
            'last_name' => 'Silva',
            'email' => 'existing@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_can_show_a_customer(): void
    {
        Sanctum::actingAs($this->user);

        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/v1/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $customer->id,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'email' => $customer->email,
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_when_customer_not_found(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/v1/customers/999999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_customer(): void
    {
        Sanctum::actingAs($this->user);

        $customer = Customer::factory()->create();

        $updateData = [
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'email' => 'maria@example.com',
            'phone' => '11988888888',
        ];

        $response = $this->putJson("/api/v1/customers/{$customer->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'Maria',
                    'last_name' => 'Santos',
                    'email' => 'maria@example.com',
                ]
            ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'email' => 'maria@example.com',
        ]);
    }

    /** @test */
    public function it_validates_email_uniqueness_when_updating_customer_except_itself(): void
    {
        Sanctum::actingAs($this->user);

        $customer1 = Customer::factory()->create(['email' => 'customer1@example.com']);
        $customer2 = Customer::factory()->create(['email' => 'customer2@example.com']);

        // Try to update customer2 with customer1's email
        $response = $this->putJson("/api/v1/customers/{$customer2->id}", [
            'first_name' => 'Test',
            'last_name' => 'Test',
            'email' => 'customer1@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        // Try to update customer1 with its own email (should pass)
        $response = $this->putJson("/api/v1/customers/{$customer1->id}", [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'email' => 'customer1@example.com',
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_delete_a_customer(): void
    {
        Sanctum::actingAs($this->user);

        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/v1/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Cliente removido com sucesso.'
            ]);

        $this->assertSoftDeleted('customers', [
            'id' => $customer->id,
        ]);
    }

    /** @test */
    public function it_respects_rate_limiting(): void
    {
        Sanctum::actingAs($this->user);

        // Make 61 requests (rate limit is 60 per minute)
        for ($i = 0; $i < 61; $i++) {
            $response = $this->getJson('/api/v1/customers');

            if ($i < 60) {
                $response->assertSuccessful();
            }
        }

        // 61st request should be rate limited
        $response->assertStatus(429);
    }
}
