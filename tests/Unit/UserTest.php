<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user(): void
    {
        $user = User::create([
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('João Silva', $user->name);
        $this->assertEquals('joao@example.com', $user->email);
    }

    /** @test */
    public function it_hides_password_in_array(): void
    {
        $user = User::factory()->create();

        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
    }

    /** @test */
    public function it_hides_remember_token_in_array(): void
    {
        $user = User::factory()->create();

        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('remember_token', $userArray);
    }

    /** @test */
    public function it_casts_email_verified_at_to_datetime(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
    }

    /** @test */
    public function it_can_use_factory(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
    }

    /** @test */
    public function it_hashes_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertNotEquals('password123', $user->password);
    }
}
