<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Database\Factories\ExpenseFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

        $response = $this->get('api/expenses', ['Accept' => 'application/json']);

        $response->assertOk();
        $response->assertSee($expense->id);
    }

    public function test_it_should_not_return_expenses_of_other_users(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $expense = ExpenseFactory::new()
            ->create(['user_id' => $user]);

        // Create a expense for other user
        ExpenseFactory::new()
            ->create(['user_id' => User::factory()]);

        $response = $this->get('api/expenses', ['Accept' => 'application/json']);

        $response->assertOk();
        $response
            ->assertJson(
                fn (AssertableJson $json)  =>
                $json->has('meta')
                    ->has('links')
                    ->has('expenses', 1)
                    ->has(
                        'expenses.0',
                        fn (AssertableJson $json) =>
                        $json->where('id', 1)
                            ->where('description', $expense->description)
                            ->where('value', $expense->value)
                            ->where('date', $expense->date)
                            ->etc()
                    )
            );
    }

    public function test_it_should_recieve_401(): void
    {
        $response = $this->get(
            'api/expenses',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    public function test_it_should_return_one_expense()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $expense = ExpenseFactory::new()
            ->create([
                'user_id' => $user->id
            ]);

        $response = $this->get("api/expenses/$expense->id", ['Accept' => 'application/json']);
        $response->assertOk();
        $response->assertSee($expense->id);
    }

    public function test_it_should_not_return_expense_of_another_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $expenseOfAnotherUser = ExpenseFactory::new()
            ->create(['user_id' => User::factory()]);

        $response = $this->get(
            "api/expenses/$expenseOfAnotherUser->id",
            ['Accept' => 'application/json']
        );

        $response->assertForbidden();
    }
}
