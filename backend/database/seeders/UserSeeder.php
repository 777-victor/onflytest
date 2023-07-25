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
            ->has(Expense::factory()->count(1))
            ->create([
                'name' => 'Victor',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
            ]);
    }
}
