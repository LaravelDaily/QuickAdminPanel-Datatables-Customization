<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $user = [
            'name'           => 'Admin',
            'email'          => 'admin@admin.com',
            'password'       => '$2y$10$xrqXr0ZyJYlmIS95bBRVP.ZbLNuSC.S5UlelqrJdGsbdJ5LjLtTvK',
        ];

        User::create($user);
    }
}
