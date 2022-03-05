<?php

use Illuminate\Database\Seeder;
use Ultraware\Roles\Models\Role;

class BusinessManagerRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
			'name' => 'Руководитель бизнес-единицы',
			'slug' => 'business-manager'
		]);
    }
}
