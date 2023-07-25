<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Database\Factories\ExpenseFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExpenseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_return_expenses(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $expense = ExpenseFactory::new()
            ->create([
                'user_id' => $user->id
            ]);

        $response = $this->get(
            'api/expenses',
            ['Accept' => 'application/json']
        );

        $response->assertOk();
        $response->assertSee($expense->description);
    }

    public function test_it_should_recieve_401(): void
    {
        $response = $this->get(
            'api/expenses',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }
}
