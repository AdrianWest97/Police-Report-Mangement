<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class RolePermissionTableSeeder extends Seeder
{
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');
        Role::create(['name'=>'admin', 'guard_name' => 'api']);
        Role::create(['name'=>'user','guard_name' => 'api']);
        Role::create(['name'=>'thirdParty','guard_name' => 'api']);
    }
}