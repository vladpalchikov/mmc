<?php

use Illuminate\Database\Seeder;
use Ultraware\Roles\Models\Role;
use Ultraware\Roles\Models\Permission;


class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Role::truncate();

    	Role::create([
			'name' => 'Главный администратор',
			'slug' => 'admin'
		]);

        Role::create([
			'name' => 'Администратор',
			'slug' => 'administrator'
		]);

		Role::create([
			'name' => 'Менеджер ТМ',
			'slug' => 'managertm'
		]);

		Role::create([
			'name' => 'Старший Менеджер ТМ',
			'slug' => 'managertmsn'
		]);

		Role::create([
			'name' => 'Менеджер МУ',
			'slug' => 'managermu'
		]);

		Role::create([
			'name' => 'Старший Менеджер МУ',
			'slug' => 'managermusn'
		]);

		Role::create([
			'name' => 'Кассир',
			'slug' => 'cashier'
		]);

		Permission::create([
			'name' => 'Трудовая миграция',
			'slug' => 'tm',
		]);

		Permission::create([
			'name' => 'Миграционный учет',
			'slug' => 'mu',
		]);
    }
}
