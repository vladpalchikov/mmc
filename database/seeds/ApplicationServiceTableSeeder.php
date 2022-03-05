<?php

use Illuminate\Database\Seeder;

class ApplicationServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MMC\Models\ApplicationService::class, 15)->create();
    }
}
