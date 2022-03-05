<?php

namespace MMC\Forms\TM;

use Kris\LaravelFormBuilder\Form;

class ForeignerDmsForm extends Form
{
    public function buildForm()
    {
        $data = $this->getData();
        $this
            ->add('_', 'static', [
                'tag' => 'h3',
                'attr' => ['class' => 'form-control-static col-md-12'],
                'value' => 'Страхователь'
            ])
            ->add('dms_surname', 'text', [
                'label' => 'Фамилия',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'rules' => 'required',
                'attr' => ['max' => '37', 'autofocus' => 'autofocus']
            ])
            ->add('dms_name', 'text', [
                'label' => 'Имя',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'rules' => 'required',
                'attr' => ['max' => '37']
            ])
            ->add('dms_middle_name', 'text', [
                'wrapper' => ['class' => 'form-group col-md-4 uppercase-input'],
                'label' => 'Отчество',
                'attr' => ['max' => '37']
            ])
            ->add('dms_birthday', 'date', [
                'label' => 'Дата рождения',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'rules' => 'required'
            ])
            ->add('dms_nationality', 'text', [
                'label' => 'Гражданство',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => 31, 'placeholder' => 'Строка 1']
            ])
            ->add('dms_gender', 'choice', [
                'label' => 'Пол',
                'choices' => ['Мужской', 'Женский'],
                'expanded' => true,
                'label_attr' => ['class' => 'choices-label'],
                'selected' => $this->getModel() ? [$this->getModel()->gender] : [0],
                'wrapper' => ['class' => 'form-group choice-group form-group col-md-12']
            ])

            ->add('dms_registration_date', 'date', [
                'label' => 'Дата регистрации',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'rules' => 'required'
            ])

            ->add('dms_address', 'text', [
                'label' => 'Адрес',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'rules' => 'required',
                'value' => $this->getModel() ? $this->getModel()->address : 'Самарская обл. ',
                'attr' => ['max' => '26']
            ])
            ->add('dms_address_line2', 'text', [
                'label' => 'Адрес [Линия 2]',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 2'],
                'label_attr' => ['class' => 'hide']
            ])
            ->add('dms_address_line3', 'text', [
                'label' => 'Адрес [Линия 3]',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 3'],
                'label_attr' => ['class' => 'hide']
            ])

            ->add('dms_document', 'text', [
                'label' => 'Документ',
                'rules' => 'required',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'value' => $this->getModel() ? $this->getModel()->document_name : 'паспорт',
                'attr' => ['max' => '11']
            ])
            ->add('dms_document_series', 'text', [
                'label' => 'Серия документа',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'attr' => ['max' => '7']
            ])
            ->add('dms_document_number', 'text', [
                'label' => 'Номер документа',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'rules' => 'required',
                'attr' => ['max' => '11']
            ])
            ->add('dms_document_date', 'date', [
                'label' => 'Дата выдачи',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'rules' => 'required'
            ])
            ->add('dms_document_issuedby', 'text', [
                'label' => 'Кем выдан',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'rules' => 'required',
                'attr' => ['max' => '120']
            ])

            ->add('dms_registration_ip_date', 'date', [
                'label' => 'Дата регистрации ИП',
                'wrapper' => ['class' => 'form-group col-md-3']
            ])

            ->add('dms_registration_document', 'text', [
                'label' => 'Документ, подтверждающий факт внесения в ЕГРИП записи о государственной регистрации ИП',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '64']
            ])

            ->add('dms_payment', 'choice', [
                'label' => 'Способ оплаты',
                'wrapper' => ['class' => 'form-group col-md-8 choice-group'],
                'label_attr' => ['class' => 'choices-label'],
                'rules' => 'required',
                'attr' => ['max' => '11'],
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    0 => 'Наличными',
                    1 => 'Безналичный расчет'
                ]
            ])

            ->add('dms_receipt', 'text', [
                'label' => 'No П/П или No квитанции',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '11']
            ])

            ->add('dms_contract_date', 'date', [
                'label' => 'Дата заключения Договора',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'rules' => 'required'
            ])

            ->add('dms_policy_date_from', 'date', [
                'label' => 'Срок действия полиса От',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'rules' => 'required'
            ])
            ->add('dms_policy_date_to', 'date', [
                'label' => 'Срок действия полиса До',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'rules' => 'required'
            ])

			->add('submit', 'submit', [
                'label' => 'Сохранить',
                'attr' => ['class' => 'btn btn-success pull-left ml15']
            ])
            ->add('link', 'static', [
                'tag' => 'a',
                'attr' => ['class' => 'btn btn-default pull-left ml15', 'href' => '/operator/foreigners/'.$data['id']],
                'value' => 'Отменить',
                'wrapper' => ['class' => 'pull-left mr10'],
                'label' => ''
            ]);
    }
}
