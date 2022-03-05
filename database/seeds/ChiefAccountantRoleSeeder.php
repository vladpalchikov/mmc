<?php

use Illuminate\Database\Seeder;
use Ultraware\Roles\Models\Role;

class ChiefAccountantRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
			'name' => 'Главный бухгалтер',
			'slug' => 'chief.accountant'
		]);
    }
}
