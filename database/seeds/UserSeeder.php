<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Default User',
            'email' => 'user@email.com',
            'password' => bcrypt('123123'),
        ]);
    }
}
