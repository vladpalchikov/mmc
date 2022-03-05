<?php

use Illuminate\Database\Seeder;
use Ultraware\Roles\Models\Role;
use Ultraware\Roles\Models\Permission;


class ModulesPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'Блок Гражданство',
            'slug' => 'bg'
        ]);
    }
}
