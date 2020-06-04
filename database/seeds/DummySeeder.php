<?php
/**
 * Copyright (c) 2020.
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 */

use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $user = \App\Models\User::where('email', 'admin@maarif.com')->first();
            $admin = new \App\Models\Admin;
            $admin->user_id = $user->id;
            $admin->name = 'Admin Maarif';
            $admin->phone = '0812';
            if (!$admin->save()){
                throw new Exception('Failed to add admin');
            }
            echo `Succeeed
            `;
            echo ' ';
            return;
        }catch (Exception $e){
            echo `{$e->getMessage()}
            `;
            echo ' ';
            return;
        }
    }
}
