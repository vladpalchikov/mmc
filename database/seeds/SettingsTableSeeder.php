<?php

use Illuminate\Database\Seeder;
use MMC\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Setting::saveSetting('inn_lock', 'false');
        Setting::saveSetting('inn_success', date('Y-m-d H:i:s'));
    }
}
