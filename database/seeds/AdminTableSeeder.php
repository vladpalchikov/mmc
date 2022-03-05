<?php

use Illuminate\Database\Seeder;
use Ultraware\Roles\Models\Role;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (MMC\Models\User::all() as $user) {
            $role = Role::where('slug', '=', 'managertm')->first();
            $user->detachAllRoles();
            $user->attachRole($role);
        }

        if (\MMC\Models\User::where('login', '=', 'admin')->count() == 0) {
            $admin = \MMC\Models\User::create([
                'login' => 'admin',
                'password' => bcrypt('secret')
            ]);
        } else {
            $admin = \MMC\Models\User::where('login', '=', 'admin')->first();
        }

        $role = Role::where('slug', '=', 'admin')->first();
        $admin->detachAllRoles();
        $admin->attachRole($role);
    }
}
