<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->has(Expense::factory()->count(3))
            ->create([
                'name' => 'Victor',
                'email' => 'victorboaventcampos@gmail.com',
                'password' => Hash::make('12345678'),
            ]);

        User::factory()
            ->has(Expense::factory()->count(3))
            ->create([
                'name' => 'Victor2',
                'email' => 'victor@admin.com',
                'password' => Hash::make('12345678'),
            ]);

        User::factory()
            ->has(Expense::factory()->count(2))
            ->create([
                'name' => 'Victor3',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
            ]);
    }
}
