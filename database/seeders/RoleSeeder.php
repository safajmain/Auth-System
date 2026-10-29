<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->updateOrInsert(
            ['role_id' => 1],
            ['role_name' => 'user']
        );

        DB::table('roles')->updateOrInsert(
            ['role_id' => 2],
            ['role_name' => 'gym_owner']
        );

        DB::table('roles')->updateOrInsert(
            ['role_id' => 3],
            ['role_name' => 'admin']
        );
    }
}
