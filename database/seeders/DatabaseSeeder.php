<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Division::create([
            'name' => 'div_1'
        ]);

        User::create([
            'username' => 'admin1',
            'password' => Hash::make('admin1'),
            'role' => 'admin'
        ]);

        User::create([
            'username' => 'user1',
            'password' => Hash::make('user1'),
            'role' => 'user'
        ]);
    }
}
