<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $accounts = [
            ['name' => 'Bendahara', 'email' => 'bendahara@example.com', 'role' => 'bendahara'],
            ['name' => 'User SPJ', 'email' => 'user@example.com', 'role' => 'user'],
            ['name' => 'Visitor 1', 'email' => 'visitor1@example.com', 'role' => 'visitor1'],
            ['name' => 'Visitor 2', 'email' => 'visitor2@example.com', 'role' => 'visitor2'],
            ['name' => 'Kepala Dinas', 'email' => 'kepala.dinas@example.com', 'role' => 'kepala_dinas'],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                [...$account, 'password' => 'password'],
            );
        }
    }
}
