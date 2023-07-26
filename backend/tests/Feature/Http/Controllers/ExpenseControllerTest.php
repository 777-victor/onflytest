<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Database\Factories\ExpenseFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpenseControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        //setUp the default user and auth for requests
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_it_should_create_expense(): void
    {

        $expenseAtributes = array(
            'description' => $this->faker->sentence(nbWords: 2),
            'value' => $this->faker->randomNumber(),
            'date' => $this->faker->date(),
            'user_id' => $this->user->id
        );

        $response = $this->post(
            'api/expenses',
            $expenseAtributes,
            ['Accept' => 'application/json']
        );

        $response->assertCreated();
    }

    /**
     * @dataProvider shouldNotCreateOrUpdateExpenseDataProvider
     */
    public function test_it_should_not_create_expense($expenseAtributes, $invalidAttributes): void
    {
        $expenseAtributes['user_id'] = $expenseAtributes['user_id'] ?? $this->user->id;
        $response = $this->post(
            'api/expenses',
            $expenseAtributes,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertInvalid($invalidAttributes);
    }

    protected function shouldNotCreateOrUpdateExpenseDataProvider()
    {
        $faker = \Faker\Factory::create();

        yield 'Empty description' => [
            array(
                'description' => null,
                'value' => $faker->randomNumber(),
                'date' => $faker->date()
            ),
            'description'
        ];

        yield 'Description has more than 191 chars' => [
            array(
                'description' => Str::random(200),
                'value' => $faker->randomNumber(),
                'date' => $faker->date()
            ),
            'description'
        ];

        yield 'Value is 0' => [
            array(
                'description' => $faker->sentence(nbWords: 2),
                'value' => 0,
                'date' => $faker->date()
            ),
            'value'
        ];

        yield 'Value less than 0' => [
            array(
                'description' => $faker->sentence(nbWords: 2),
                'value' => -1,
                'date' => $faker->date()
            ),
            'value'
        ];

        yield 'Empty Date' => [
            array(
                'description' => $faker->sentence(nbWords: 2),
                'value' => $faker->randomNumber(),
                'date' => null
            ),
            'date'
        ];

        yield 'Date in future' => [
            array(
                'description' => $faker->sentence(nbWords: 2),
                'value' => $faker->randomNumber(),
                'date' => date('2080-m-d')
            ),
            'date'
        ];

        yield 'Invalid user_id' => [
            array(
                'description' => $faker->sentence(nbWords: 2),
                'value' => $faker->randomNumber(),
                'date' => $faker->date(),
                'user_id' => 2413124
            ),
            'user_id'
        ];
    }

    /**
     * @dataProvider shouldNotCreateOrUpdateExpenseDataProvider
     */
    public function test_it_should_not_update_expense($expenseAtributes, $invalidAttributes): void
    {
        $expense = ExpenseFactory::new()->create(['user_id' => $this->user->id]);
        $expenseAtributes['id'] = $expense->id;
        $expenseAtributes['user_id'] = $expenseAtributes['user_id'] ?? $this->user->id;
        $response = $this->put(
            "api/expenses/$expense->id",
            $expenseAtributes,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertInvalid($invalidAttributes);
    }

    public function test_it_should_update_expense(): void
    {
        $expense = ExpenseFactory::new()
            ->create(['user_id' => $this->user->id]);

        $atributes = $expense->getAttributes();
        $newDescription = $expense->description . ' some text';
        $newValue = $expense->value + 1;
        $atributes['description'] = $newDescription;
        $atributes['value'] = $newValue;

        $response = $this->put(
            "api/expenses/$expense->id",
            $atributes,
            ['Accept' => 'application/json']
        );

        $response->assertOk();
        $response->assertJsonFragment([
            'description' => $newDescription,
            'value' => $newValue
        ]);
    }

    public function test_it_should_not_update_expense_of_other_user(): void
    {
        $expenseOfOtherUser = ExpenseFactory::new()
            ->create([
                'user_id' => User::factory()
            ]);

        $atributes = $expenseOfOtherUser->getAttributes();
        $newDescription = $expenseOfOtherUser->description . ' some text';
        $atributes['description'] = $newDescription;

        $response = $this->put(
            "api/expenses/$expenseOfOtherUser->id",
            $atributes,
            ['Accept' => 'application/json']
        );

        $response->assertForbidden();
    }

    public function test_it_should_return_expenses(): void
    {

        $expense = ExpenseFactory::new()
            ->create([
                'user_id' => $this->user->id
            ]);

        $response = $this->get('api/expenses', ['Accept' => 'application/json']);

        $response->assertOk();
        $response->assertSee($expense->id);
    }

    public function test_it_should_not_return_expenses_of_other_users(): void
    {
        $expense = ExpenseFactory::new()
            ->create(['user_id' => $this->user->id]);

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

    public function test_it_should_return_one_expense()
    {

        $expense = ExpenseFactory::new()
            ->create([
                'user_id' => $this->user->id
            ]);

        $response = $this->get("api/expenses/$expense->id", ['Accept' => 'application/json']);
        $response->assertOk();
        $response->assertSee($expense->id);
    }

    public function test_it_should_not_return_expense_of_other_user()
    {
        $expenseOfAnotherUser = ExpenseFactory::new()
            ->create(['user_id' => User::factory()]);

        $response = $this->get(
            "api/expenses/$expenseOfAnotherUser->id",
            ['Accept' => 'application/json']
        );

        $response->assertForbidden();
    }

    public function test_it_should_delete_expense()
    {
        $expense = ExpenseFactory::new()->create(['user_id' => $this->user->id]);

        $response = $this->delete(
            "api/expenses/$expense->id",
            ['Accept' => 'application/json']
        );
        $response->assertOk();
        $response->assertJson(['message' => 'Expense deleted successfully']);
    }

    public function test_it_should_not_delete_expense_of_other_user()
    {
        $expenseOfAnotherUser = ExpenseFactory::new()
            ->create(['user_id' => User::factory()]);

        $response = $this->delete(
            "api/expenses/$expenseOfAnotherUser->id",
            ['Accept' => 'application/json']
        );

        $response->assertForbidden();
    }
}
