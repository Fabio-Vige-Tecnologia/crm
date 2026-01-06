<?php

namespace Tests\Unit;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_customer(): void
    {
        $customer = Customer::create([
            'first_name' => 'João',
            'last_name' => 'Silva',
            'email' => 'joao@example.com',
            'phone' => '11999999999',
        ]);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('João', $customer->first_name);
        $this->assertEquals('Silva', $customer->last_name);
        $this->assertEquals('joao@example.com', $customer->email);
        $this->assertEquals('11999999999', $customer->phone);
    }

    /** @test */
    public function it_has_fillable_attributes(): void
    {
        $customer = new Customer();

        $this->assertEquals(
            ['first_name', 'last_name', 'email', 'phone'],
            $customer->getFillable()
        );
    }

    /** @test */
    public function it_uses_soft_deletes(): void
    {
        $customer = Customer::factory()->create();

        $customer->delete();

        $this->assertSoftDeleted('customers', [
            'id' => $customer->id,
        ]);

        $this->assertNotNull($customer->fresh()->deleted_at);
    }

    /** @test */
    public function it_can_restore_soft_deleted_customer(): void
    {
        $customer = Customer::factory()->create();

        $customer->delete();
        $this->assertSoftDeleted('customers', ['id' => $customer->id]);

        $customer->restore();
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function it_casts_dates_correctly(): void
    {
        $customer = Customer::factory()->create();

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $customer->created_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $customer->updated_at);
    }

    /** @test */
    public function it_can_use_factory(): void
    {
        $customer = Customer::factory()->create();

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertNotNull($customer->first_name);
        $this->assertNotNull($customer->last_name);
        $this->assertNotNull($customer->email);
    }

    /** @test */
    public function phone_is_optional(): void
    {
        $customer = Customer::create([
            'first_name' => 'João',
            'last_name' => 'Silva',
            'email' => 'joao@example.com',
        ]);

        $this->assertNull($customer->phone);
    }
}
