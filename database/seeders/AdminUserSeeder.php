<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'fraudreportwebsite@gmail.com';
        $user = User::where('email', $email)->first();
        if (! $user) {
            User::create([
                'name' => 'Fraud Report Administrator',
                'email' => $email,
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'role' => 'admin',
                'status' => 'active',
            ]);
        } else {
            $user->update([
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
        }
    }
}
