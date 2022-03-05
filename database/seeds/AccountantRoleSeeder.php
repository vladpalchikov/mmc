<?php

use Illuminate\Database\Seeder;
use Ultraware\Roles\Models\Role;
use Ultraware\Roles\Models\Permission;

class AccountantRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Role::create([
			'name' => 'Бухгалтер',
			'slug' => 'accountant'
		]);
    }
}
