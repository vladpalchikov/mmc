<?php

use Illuminate\Database\Seeder;

class ServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(MMC\Models\Service::class, 15)->create();
    
    	\MMC\Models\Service::create([
			'name' => 'Медицина',
			'description' => 'Подготовка необходимых форм и документов, организация и направление заказчиков на предоставляемые медицинские услуги',
			'price' => 3800,
			'service_included' => 'Test'
		]);

		\MMC\Models\Service::create([
			'name' => 'Мигр. учет',
			'description' => 'Подготовка необходимых форм и документов, их первичная проверка, организация и направление заказчиков для оказания услуг по миграционному учету',
			'price' => 1000,
			'service_included' => 'Test'
		]);

		\MMC\Models\Service::create([
			'name' => 'Патент',
			'description' => 'Правовое сопровождение и консультированию по вопросам получения патента, формирования пакета документов, необходимых для получения патента',
			'price' => 2500,
			'service_included' => 'Test'
		]);

		\MMC\Models\Service::create([
			'name' => 'Перевод',
			'description' => 'Подготовка необходимых форм и документов, их первичная проверка, организация и направление заказчиков для оказания услуг по переводу и нотариальному удостоверению перевода',
			'price' => 1050,
			'service_included' => 'Test'
		]);

		\MMC\Models\Service::create([
			'name' => 'Страхование',
			'description' => 'Подготовка необходимых форм и документов, их первичная проверка, организация и направление заказчиков для оказания услуг по заключению договора добровольного медицинского страхования',
			'price' => 3500,
			'service_included' => 'Test'
		]);

		\MMC\Models\Service::create([
			'name' => 'Тестирование',
			'description' => 'Подготовка необходимых форм и документов, организация и направление заказчиков на тестирование иностранного гражданина по русскому языку как иностранному, истории России и основам законодательства РФ',
			'price' => 4000,
			'service_included' => 'Test'
		]);
    }
}
