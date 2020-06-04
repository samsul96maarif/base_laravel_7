<?php
/**
 * Copyright (c) 2019.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'email' => 'samsulma828@gmail.com',
            'role' => 0,
            'password' => bcrypt('maarifComp'),
        ]);
    }
}
