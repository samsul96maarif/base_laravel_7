<?php

use Illuminate\Database\Seeder;

class MaarifCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            $user = new \App\Models\User;
            $user->email = 'admin@maarif.com';
            $user->role = 1;
            $user->password = bcrypt('adminmaarif');
            if (!$user->save()){
                throw new Exception('Failed to add user');
            }
            $company = new \App\Models\Company;
            $company->user_id = $user->id;
            $company->name = 'Maarif Comp';
            $company->slug = 'maarif-comp';
            if (!$company->save()){
                throw new Exception('Failed to add Company');
            }

            $admin = new \App\Models\Admin;
            $admin->user_id = $user->id;
            $admin->name = 'Admin Maarif';
            $admin->phone = '0812';
            if (!$admin->save()){
                throw new Exception('Failed to add admin');
            }

            \Illuminate\Support\Facades\DB::commit();
            echo 'Succeed';
            echo `
`;
            echo ' ';
            return;
        }catch (Exception $e){
            \Illuminate\Support\Facades\DB::rollBack();
            echo $e->getMessage();
            echo `
            `;
            echo ' ';
            return;
        }
    }
}
