<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::whereEmail('admin@sigma.com')->first();
        if ($user)
            $user->delete();


        User::insert([
            'email' => 'admin@sigma.com',
            'name' => 'Sigma Admin',
            'password' => Hash::make("allowme@1")
        ]);
    }
}
