<?php 

namespace MMC\Console\Commands;

use Illuminate\Console\Command;

class VendorFix extends Command
{
	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmc:vendor-fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[Служебный] Фикс папки vendor, использовать при развертывании приложения';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $vendorPath = base_path('vendor');

        // Фикс поиска адреса в Яндексе
        $yandexGeoPath = $vendorPath.'/yandex/geo/source/Yandex/Geo/Api.php';
        $yandexGeoContent = file_get_contents($yandexGeoPath);
        $yandexGeoContent = str_replace('$longitude = (float)$longitude;', '$longitude = str_replace(\',\', \'.\', $longitude);', $yandexGeoContent);
        $yandexGeoContent = str_replace('$latitude = (float)$latitude;', '$latitude = str_replace(\',\', \'.\', $latitude);', $yandexGeoContent);
        $yandexGeoContent = str_replace('$this->_filters[\'geocode\'] = sprintf(\'%F,%F\', $longitude, $latitude);', '$this->_filters["geocode"] = sprintf("%s,%s", $longitude, $latitude);', $yandexGeoContent);
        file_put_contents($yandexGeoPath, $yandexGeoContent);
        
        // Фикс названия приложения в пакете для бекапа
        $backupManagerPath = $vendorPath.'/backpack/backupmanager/src/app/Http/Controllers/BackupController.php';
        $backupControllerContent = file_get_contents($backupManagerPath);
        $backupControllerContent = str_replace('App', 'MMC', $backupControllerContent);
        file_put_contents($backupManagerPath, $backupControllerContent);
    }
	
}