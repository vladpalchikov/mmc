<?php

namespace MMC\Forms\TM;

use Kris\LaravelFormBuilder\Form;
use \MMC\Models\Ifns;
use \MMC\Models\Client;

class ForeignerForm extends Form
{
    public function buildForm()
    {
        $foreigner_id = $this->getModel() ? (isset($this->getModel()->id) ? $this->getModel()->id : '') : '';

        $ifns[0] = 'Выберите ИФНС';
        foreach (Ifns::all() as $item) {
            $ifns[$item->id] = $item->kod.' - '.$item->name;
        }

        $data = $this->getData();

        $document_series = '';
        $document_number = '';

        if ($this->getModel()) {
            $document_series = $this->getModel()->document_series;
            $document_number = $this->getModel()->document_number;
        } else {
            if (\Request::has('document_series')) {
                $document_series = \Request::get('document_series');
            }

            if (\Request::has('document_number')) {
                $document_number = \Request::get('document_number');
            }
        }

        $this
            ->add('oktmo_fail', 'hidden', [
                'value' => $this->getModel() ? (isset($this->getModel()->oktmo) ? 0 : 1) : 1
            ])
            ->add('inn_check', 'hidden', [
                'default_value' => 0
            ])
            ->add('surname', 'text', [
                'label' => 'Фамилия',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'rules' => 'required',
                'attr' => ['max' => '37', 'autofocus' => 'autofocus'],
            ])
            ->add('name', 'text', [
                'label' => 'Имя',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'rules' => 'required',
                'attr' => ['max' => '37'],
            ])
            ->add('middle_name', 'text', [
                'wrapper' => ['class' => 'form-group col-md-4 uppercase-input'],
                'label' => 'Отчество',
                'attr' => ['max' => '37'],
            ])
            ->add('birthday', 'date', [
                'label' => 'Год рождения',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'rules' => 'required'
            ])
            ->add('nationality', 'text', [
                'label' => 'Гражданство',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => 31, 'placeholder' => 'Строка 1'],
                'rules' => 'required'
            ])
            ->add('nationality_line2', 'text', [
                'label' => 'Гражданство',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => 42, 'placeholder' => 'Строка 2'],
                'label_attr' => ['class' => 'hide']
            ])
            ->add('gender', 'choice', [
                'label' => 'Пол',
                'choices' => ['Мужской', 'Женский'],
                'expanded' => true,
                'label_attr' => ['class' => 'choices-label'],
                'selected' => $this->getModel() ? [$this->getModel()->gender] : [0],
                'wrapper' => ['class' => 'form-group choice-group form-group col-md-12']
            ])
            ->add('document_name', 'text', [
                'label' => 'Документ',
                'rules' => 'required',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'value' => $this->getModel() ? $this->getModel()->document_name : 'ПАСПОРТ',
                'attr' => ['max' => '11']
            ])
            ->add('document_series', 'text', [
                'label' => 'Серия документа',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'attr' => ['max' => '7', 'pattern' => '[a-zA-Zа-яА-Я0-9]{0,7}', 'title' => 'Только цифры и буквы'],
                'value' => $document_series
            ])
            ->add('document_number', 'text', [
                'label' => 'Номер документа',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'rules' => 'required',
                'attr' => ['max' => '11', 'pattern' => '[a-zA-Zа-яА-Я0-9]{1,11}', 'title' => 'Только цифры и буквы'],
                'value' => $document_number
            ])
            ->add('document_date', 'date', [
                'label' => 'Дата выдачи',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'rules' => 'required'
            ])
            ->add('document_issuedby', 'text', [
                'label' => 'Кем выдан',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'rules' => 'required',
                'attr' => ['max' => '120']
            ])
            ->add('inn', 'text', [
                'label' => 'ИНН (укажите 0 если отсутствует)',
                'rules' => 'required',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'attr' => ['max' => '12', 'pattern' => '[0-9]{1}|[0-9]{12,12}', 'title' => 'Должно быть 12 цифр или 0'],
            ])
            ->add('get_inn', 'static', [
                'tag' => 'a',
                'attr' => ['class' => 'btn btn-info pull-left get-inn'],
                'value' => 'Получить ИНН',
                'wrapper' => ['class' => 'form-group col-md-12 mb20'],
                'label' => ''
            ])
            ->add('is_host_only', 'checkbox', [
                'label' => 'Указать вручную (при отсутствии в списке)',
                'value' => false,
                'checked' => false,
                'label_attr' => ['class' => 'control-label mt0'],
                'wrapper' => ['class' => 'form-group choice-group col-md-12'],
            ])
            ->add('client_name', 'text', [
                'label' => 'Наименование ЮЛ / ФИО ФЛ',
                'label_attr' => ['class' => 'required'],
                'wrapper' => ['class' => 'form-group col-md-9 hide']
            ])
            ->add('client_type', 'choice', [
                'label' => 'Тип',
                'choices' => ['Юр. лицо', 'Физ. лицо'],
                'expanded' => true,
                'selected' => 0,
                'label_attr' => ['class' => 'choices-label'],
                'wrapper' => ['class' => 'form-group choice-group form-group col-md-12 hide']
            ])
            ->add('address', 'text', [
                'label' => 'Адрес регистрации по месту пребывания',
                'wrapper' => ['class' => 'form-group col-md-9'],
                'rules' => 'required',
                'value' => $this->getModel() ? $this->getModel()->address : 'Самарская обл. ',
                'attr' => ['max' => '26']
            ])
            ->add('address_line2', 'text', [
                'label' => 'Адрес регистрации по месту прибывания [Линия 2]',
                'wrapper' => ['class' => 'form-group col-md-9'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 2']
            ])
            ->add('address_line3', 'text', [
                'label' => 'Адрес регистрации по месту прибывания [Линия 3]',
                'wrapper' => ['class' => 'form-group col-md-9'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 3']
            ])
            ->add('registration_date', 'date', [
                'label' => 'Срок регистрации',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'rules' => 'required'
            ])
            ->add('phone', 'text', [
                'label' => 'Контактный телефон',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['placeholder' => '8...'],
                'rules' => 'required'
            ])
            ->add('oktmo', 'text', [
                'label' => 'ОКТМО',
                'rules' => 'required',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ])
            ->add('ifns_name', 'choice', [
                'label' => 'ИФНС',
                'choices' => $ifns,
                'rules' => 'required',
                'selected' => $this->getModel() ? $this->getModel()->ifns_name : [0],
                'label_attr' => ['class' => 'choices-label'],
                'wrapper' => ['class' => 'form-group col-md-9'],
            ])
            ->add('oktmo_btn', 'static', [
                'tag' => 'a',
                'attr' => ['class' => 'btn btn-info pull-left ml15 get-oktmo'],
                'value' => 'Получить ОКТМО',
                'wrapper' => ['class' => 'pull-left mr10'],
                'label' => ''
            ])
			->add('submit', 'submit', [
                'label' => 'Сохранить',
                'attr' => ['class' => 'btn btn-success pull-left ml15']
            ])
            ->add('link', 'static', [
                'tag' => 'a',
                'attr' => ['class' => 'btn btn-default pull-left ml15', 'href' => '/operator/foreigners/'.$foreigner_id],
                'value' => 'Отменить',
                'wrapper' => ['class' => 'pull-left mr10'],
                'label' => ''
            ]);
    }
}
