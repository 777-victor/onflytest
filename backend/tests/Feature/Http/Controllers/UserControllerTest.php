<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_should_register_user(): void
    {
        $userData = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ];

        $response = $this->post(
            'api/register',
            $userData,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(201);
        $response->assertJsonStructure(['user', 'token']);
    }


    /**
     * @dataProvider shouldNotCreateOrUpdateExpenseDataProvider
     */
    public function test_should_not_register_user($userData, $nameOfInvalidAttributes): void
    {
        $response = $this->post(
            'api/register',
            $userData,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertInvalid($nameOfInvalidAttributes);
    }

    protected function shouldNotCreateOrUpdateExpenseDataProvider()
    {
        $faker = \Faker\Factory::create();

        yield 'Empty name' => [
            array(
                'name' => null,
                'email' => $faker->unique()->safeEmail(),
                'password' => '12345678',
                'password_confirmation' => '12345678',
            ),
            'name'
        ];

        yield 'Name has more than 255 chars' => [
            array(
                'name' => Str::random(256),
                'email' => $faker->unique()->safeEmail(),
                'password' => '12345678',
                'password_confirmation' => '12345678',
            ),
            'name'
        ];

        yield 'Empty email' => [
            array(
                'name' => $faker->name(),
                'email' => null,
                'password' => '12345678',
                'password_confirmation' => '12345678',
            ),
            'email'
        ];

        yield 'Email invalid' => [
            array(
                'name' => $faker->name(),
                'email' => $faker->word(),
                'password' => '12345678',
                'password_confirmation' => '12345678',
            ),
            'email'
        ];

        yield 'Password invalid' => [
            array(
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => '1234',
                'password_confirmation' => '1234',
            ),
            'password'
        ];

        yield 'Password confirmation dont match' => [
            array(
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => '12345678',
                'password_confirmation' => '123456789',
            ),
            'password'
        ];
    }

    public function test_get_authenticated_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->get('api/user', ['Accept' => 'application/json']);

        $response->assertOk();
        $response->assertJsonStructure(['user']);
    }
}
