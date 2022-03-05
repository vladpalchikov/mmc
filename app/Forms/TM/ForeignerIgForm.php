<?php

namespace MMC\Forms\TM;

use Kris\LaravelFormBuilder\Form;

class ForeignerIgForm extends Form
{
    public function buildForm()
    {
        $data = $this->getData();
        $this
            ->add('_', 'static', [
                'tag' => 'div',
                'attr' => ['class' => 'form-control-static col-md-12 text-center text-bold'],
                'value' => 'СВЕДЕНИЯ О ЛИЦЕ, ПОДЛЕЖАЩЕМ ПОСТАНОВКЕ НА УЧЕТ В МЕСТО ПРЕБЫВАНИЯ'
            ])
            ->add('surname', 'text', [
                'label' => 'Фамилия',
                'default_value' => $data['surname'],
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '37']
            ])
            ->add('name', 'text', [
                'label' => 'Имя',
                'default_value' => $data['name'],
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '37']
            ])
            ->add('middle_name', 'text', [
                'label' => 'Отчество',
                'default_value' => $data['middle_name'],
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '37']
            ])
            ->add('nationality', 'text', [
                'label' => 'Гражданство',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'default_value' => $data['nationality'],
                'attr' => ['max' => 31, 'placeholder' => 'Строка 1']
            ])
            ->add('nationality_line2', 'text', [
                'label' => 'Гражданство',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'default_value' => $data['nationality_line2'],
                'attr' => ['max' => 42, 'placeholder' => 'Строка 2'],
                'label_attr' => ['class' => 'hide']
            ])
            ->add('place_birthday_country', 'text', [
                'label' => 'Место рождения: Страна',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '36']
            ])
            ->add('place_birthday_city', 'text', [
                'label' => 'Место рождения: Город',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '36']
            ])
            ->add('birthday', 'date', [
                'label' => 'Дата рождения',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'default_value' => $data['birthday']
            ])
            ->add('gender', 'choice', [
                'label' => 'Пол',
                'choices' => ['Мужской', 'Женский'],
                'expanded' => true,
                'label_attr' => ['class' => 'choices-label'],
                'selected' => $data['gender'],
                'wrapper' => ['class' => 'form-group choice-group form-group col-md-12']
            ])
            ->add('address', 'text', [
                'label' => 'Адрес постоянного проживания',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'default_value' => $data['address'],
                'attr' => ['max' => '26', 'placeholder' => 'Строка 1']
            ])
            ->add('address_line2', 'text', [
                'label' => 'Адрес постоянного проживания',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'default_value' => $data['address_line2'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 2'],
                'label_attr' => ['class' => 'hide']
            ])
            ->add('address_line3', 'text', [
                'label' => 'Адрес постоянного проживания',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'default_value' => $data['address_line3'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 3'],
                'label_attr' => ['class' => 'hide']
            ])
            ->add('document_name', 'text', [
                'label' => 'Документ, удостоверяющий личность',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'default_value' => $data['document_name'],
                'attr' => ['max' => '11']
            ])
            ->add('document_series', 'text', [
                'label' => 'Серия',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'default_value' => $data['document_series'],
                'attr' => ['max' => '4']
            ])
            ->add('document_number', 'text', [
                'label' => 'Номер',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'default_value' => $data['document_number'],
                'attr' => ['max' => '10']
            ])
            ->add('document_date', 'date', [
                'label' => 'Дата выдачи',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'default_value' => $data['document_date']
            ])
            ->add('document_date_to', 'date', [
                'label' => 'Срок действия',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'default_value' => $data['document_date_to']
            ])
            ->add('__', 'static', [
                'tag' => 'div',
                'attr' => ['class' => 'form-control-static col-md-12 text-center'],
                'value' => 'Вид и реквизиты документа, подтверждающего право на пребывание (проживание) в Российской Федерации'
            ])
            ->add('residence_type', 'choice', [
                'label' => 'Вид',
                'choices' => ['Отсутствует', 'Виза', 'Вид на жительство', 'Разрешение на временное проживание'],
                'label_attr' => ['class' => 'choices-label'],
                'selected' => $this->getModel() ? [$this->getModel()->residence_type] : [0],
                'wrapper' => ['class' => 'form-group col-md-12 choice-group'],
                'expanded' => true
            ])
            ->add('residence_series', 'text', [
                'label' => 'Серия',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '4']
            ])
            ->add('residence_number', 'text', [
                'label' => 'Номер',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '9']
            ])
            ->add('residence_date_from', 'date', [
                'label' => 'Действителен от',
                'wrapper' => ['class' => 'form-group col-md-4']
            ])
            ->add('residence_date_to', 'date', [
                'label' => 'Действителен до',
                'wrapper' => ['class' => 'form-group col-md-4']
            ])
            ->add('entry_purpose', 'choice', [
                'label' => 'Цель въезда',
                'choices' => ['Служебная', 'Туризм', 'Деловая', 'Учеба', 'Работа', 'Частная', 'Транзит', 'Гуманитарная', 'Другая'],
                'label_attr' => ['class' => 'choices-label'],
                'wrapper' => ['class' => 'form-group col-md-12 choice-group'],
                'selected' => $this->getModel() ? [$this->getModel()->entry_purpose] : [4],
            ])
            ->add('enter_date_from', 'date', [
                'label' => 'Дата въезда в РФ',
                'wrapper' => ['class' => 'form-group col-md-4']
            ])
            ->add('enter_date_to', 'date', [
                'label' => 'Срок пребывания до',
                'wrapper' => ['class' => 'form-group col-md-4']
            ])
            ->add('profession', 'text', [
                'label' => 'Профессия',
                'wrapper' => ['class' => 'form-group col-md-8'],
                'attr' => ['max' => '26'],
                'default_value' => 'Разнорабочий'
            ])
            ->add('qualification', 'text', [
                'label' => 'Квалификация',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '2']
            ])
            ->add('migration_card_series', 'text', [
                'label' => 'Миграционная карта: серия',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '4']
            ])
            ->add('migration_card_number', 'text', [
                'label' => 'Миграционная карта: номер',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '7']
            ])
            ->add('representatives', 'text', [
                'label' => 'Сведения о законных представителях',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '19', 'placeholder' => 'Строка 1']
            ])
            ->add('representatives_line2', 'text', [
                'label' => 'Сведения о законных представителях',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '19', 'placeholder' => 'Строка 2'],
                'label_attr' => ['class' => 'hide']
            ])
            ->add('prev_address_line1', 'text', [
                'label' => 'Адрес прежнего места пребывания в Российской Федерации',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '19', 'placeholder' => 'Строка 1']
            ])
            ->add('prev_address_line2', 'text', [
                'label' => 'Адрес прежнего места пребывания в Российской Федерации',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '19', 'placeholder' => 'Строка 2'],
                'label_attr' => ['class' => 'hide']
            ])
            ->add('prev_address_line3', 'text', [
                'label' => 'Адрес прежнего места пребывания в Российской Федерации',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '19', 'placeholder' => 'Строка 3'],
                'label_attr' => ['class' => 'hide']
            ])

            ->add('area', 'text', [
                'label' => 'Область',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '35']
            ])
            ->add('region', 'text', [
                'label' => 'Район',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '35']
            ])
            ->add('city', 'text', [
                'label' => 'Город',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '35']
            ])
            ->add('street', 'text', [
                'label' => 'Улица',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '35']
            ])
            ->add('house', 'text', [
                'label' => 'Дом',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '4']
            ])
            ->add('housing', 'text', [
                'label' => 'Корпус',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '4']
            ])
            ->add('building', 'text', [
                'label' => 'Строение',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '4']
            ])
            ->add('flat', 'text', [
                'label' => 'Квартира',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '4']
            ])

            ->add('___', 'static', [
                'tag' => 'div',
                'attr' => ['class' => 'form-control-static col-md-12 text-center text-bold'],
                'value' => 'СВЕДЕНИЯ О МЕСТЕ ПРЕБЫВАНИЯ'
            ])

            ->add('place_area', 'text', [
                'label' => 'Область',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '36']
            ])
            ->add('place_region', 'text', [
                'label' => 'Район',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '36']
            ])
            ->add('place_city', 'text', [
                'label' => 'Город',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '36']
            ])
            ->add('place_street', 'text', [
                'label' => 'Улица',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '36']
            ])
            ->add('place_house', 'text', [
                'label' => 'Дом',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '4']
            ])
            ->add('place_housing', 'text', [
                'label' => 'Корпус',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '4']
            ])
            ->add('place_building', 'text', [
                'label' => 'Строение',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '4']
            ])
            ->add('place_flat', 'text', [
                'label' => 'Квартира',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '4']
            ])
            ->add('place_phone', 'text', [
                'label' => 'Телефон',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '10']
            ])

            ->add('____', 'static', [
                'tag' => 'div',
                'attr' => ['class' => 'form-control-static col-md-12 text-center text-bold'],
                'value' => 'СВЕДЕНИЯ О ПРИНИМАЮЩЕЙ СТОРОНЕ'
            ])
            ->add('receiving_surname', 'text', [
                'label' => 'Фамилия',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '36'],
                'default_value' => 'Шашкова'
            ])
            ->add('receiving_name', 'text', [
                'label' => 'Имя',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '36'],
                'default_value' => 'Татьяна'
            ])
            ->add('receiving_middle_name', 'text', [
                'label' => 'Отчество',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '36'],
                'default_value' => 'Витальевна'
            ])
            ->add('receiving_birthday', 'date', [
                'label' => 'Дата рождения',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'default_value' => date('Y-m-d', strtotime('10.11.1992'))
            ])
            ->add('receiving_document_name', 'text', [
                'label' => 'Документ, удостоверяющий личность',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '38'],
                'default_value' => 'паспорт'
            ])
            ->add('receiving_document_series', 'text', [
                'label' => 'Серия',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'attr' => ['max' => '4'],
                'default_value' => '3612'
            ])
            ->add('receiving_document_number', 'text', [
                'label' => 'Номер',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'attr' => ['max' => '9'],
                'default_value' => '666566'
            ])
            ->add('receiving_document_date_from', 'date', [
                'label' => 'Дата выдачи',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'default_value' => date('Y-m-d', strtotime('15.12.2012'))
            ])
            ->add('receiving_document_date_to', 'date', [
                'label' => 'Срок действия',
                'wrapper' => ['class' => 'form-group col-md-6']
            ])
            ->add('receiving_area', 'text', [
                'label' => 'Область',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '36'],
                'default_value' => 'Самарская'
            ])
            ->add('receiving_region', 'text', [
                'label' => 'Район',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '36'],
                'default_value' => 'Промышленный'
            ])
            ->add('receiving_city', 'text', [
                'label' => 'Город',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '36'],
                'default_value' => 'Самара'
            ])
            ->add('receiving_street', 'text', [
                'label' => 'Улица',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '36'],
                'default_value' => 'Ново-Вокзальная'
            ])
            ->add('receiving_house', 'text', [
                'label' => 'Дом',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '4'],
                'default_value' => '249'
            ])
            ->add('receiving_housing', 'text', [
                'label' => 'Корпус',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '4']
            ])
            ->add('receiving_building', 'text', [
                'label' => 'Строение',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '4']
            ])
            ->add('receiving_flat', 'text', [
                'label' => 'Квартира',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '4'],
                'default_value' => '63'
            ])
            ->add('receiving_phone', 'text', [
                'label' => 'Телефон',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '10']
            ])

            ->add('_____', 'static', [
                'tag' => 'div',
                'attr' => ['class' => 'form-control-static col-md-12 text-center'],
                'value' => 'Для организаций и индивидуальных предпринимателей'
            ])

            ->add('receiving_org_name', 'text', [
                'label' => 'Наименование организации',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '24', 'placeholder' => 'Строка 1']
            ])
            ->add('receiving_org_name_line2', 'text', [
                'label' => 'Наименование организации',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '28', 'placeholder' => 'Строка 2'],
                'label_attr' => ['class' => 'hide']
            ])
            ->add('receiving_org_address', 'text', [
                'label' => 'Факт. адрес',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '24', 'placeholder' => 'Строка 1']
            ])
            ->add('receiving_org_address_line2', 'text', [
                'label' => 'Факт. адрес',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '28', 'placeholder' => 'Строка 2'],
                'label_attr' => ['class' => 'hide']
            ])
            ->add('receiving_org_inn', 'text', [
                'label' => 'ИНН',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '12']
            ])
            ->add('link', 'static', [
                'tag' => 'a',
                'attr' => ['class' => 'btn btn-primary pull-left', 'href' => '/operator/foreigners/'.$data['id']],
                'value' => 'Отменить',
                'wrapper' => ['class' => 'pull-left mr10 ml15'],
                'label' => ''
            ])
            ->add('submit', 'submit', ['label' => 'Далее', 'wrapper' => ['class' => 'form-group'], 'attr' => ['class' => 'btn btn-success pull-left']]);
    }
}
