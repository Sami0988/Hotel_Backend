<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    \App\Models\User::where('email', 'admin@example.com')->update(['role' => 'admin']);
    \App\Models\User::where('email', 'reception@example.com')->update(['role' => 'reception']);
    // All other users will have the default 'guest' role
}
}
