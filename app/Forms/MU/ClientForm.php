<?php

namespace MMC\Forms\MU;

use Kris\LaravelFormBuilder\Form;

class ClientForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('type', 'choice', [
                'choices' => ['Юридическое лицо', 'Физическое лицо'],
                'wrapper' => ['class' => 'form-group col-md-12'],
                'selected' => $this->getModel() ? [$this->getModel()->type] : [0],
                'choice_options' => [
                    'wrapper' => ['class' => 'choice-wrapper'],
                    'label_attr' => ['class' => 'label-class'],
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'ФЛ/ЮЛ'
            ])
            ->add('is_host_only', 'choice', [
                'choices' => ['Представитель (оплачивает счета и может выступать принимающей стороной)', 'Только принимающая сторона'],
                'wrapper' => ['class' => 'form-group col-md-12'],
                'selected' => $this->getModel() ? [$this->getModel()->is_host_only] : [0],
                'choice_options' => [
                    'wrapper' => ['class' => 'choice-wrapper'],
                    'label_attr' => ['class' => 'label-class'],
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Тип'
            ])
            ->add('name', 'text', [
                'label' => 'Название организации / ФИО',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'rules' => 'required'
            ])

            ->add('inn', 'text', [
                'label' => 'ИНН',
                'wrapper' => ['class' => 'form-group col-md-6']
            ])

            ->add('email', 'text', [
                'label' => 'Email',
                'wrapper' => ['class' => 'form-group col-md-6']
            ])

            ->add('contact_person', 'text', [
                'label' => 'Контактное лицо',
                'wrapper' => ['class' => 'form-group col-md-6 organization']
            ])
            ->add('organization_contact_phone', 'text', [
                'label' => 'Контактный телефон',
                'wrapper' => ['class' => 'form-group col-md-6 organization']
            ])

            ->add('organization_manager', 'text', [
                'label' => 'ФИО директора',
                'wrapper' => ['class' => 'form-group col-md-6 organization']
            ])

            ->add('organization_form', 'text', [
                'label' => 'Организационная форма',
                'wrapper' => ['class' => 'form-group col-md-6 organization']
            ])
            ->add('organization_fullname', 'text', [
                'label' => 'Полное наименование организации',
                'wrapper' => ['class' => 'form-group col-md-12 organization']
            ])

            ->add('organization_address', 'text', [
                'label' => 'Юридический адрес / Адрес регистрации граждан',
                'wrapper' => ['class' => 'form-group col-md-12 organization'],
                'attr' => ['max' => '26', 'placeholder' => 'Строка 1']
            ])
            ->add('organization_address_line2', 'text', [
                'label' => 'Адрес [Линия 2]',
                'wrapper' => ['class' => 'form-group col-md-12 organization'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 2']
            ])
            ->add('organization_address_line3', 'text', [
                'label' => 'Адрес [Линия 3]',
                'wrapper' => ['class' => 'form-group col-md-12 organization'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 3']
            ])
            ->add('requisite_title', 'static', [
                'tag' => 'h4',
                'attr' => ['class' => 'form-control-static organization'],
                'wrapper' => ['class' => 'form-group col-md-12 organization'],
                'value' => 'Реквизиты',
                'label' => ''
            ])
            ->add('organization_requisite_inn', 'text', [
                'label' => 'ИНН',
                'wrapper' => ['class' => 'form-group col-md-6 organization']
            ])
            ->add('organization_requisite_account', 'text', [
                'label' => 'Номер счета',
                'wrapper' => ['class' => 'form-group col-md-6 organization']
            ])
            ->add('organization_requisite_bank', 'text', [
                'label' => 'Наименование банка',
                'wrapper' => ['class' => 'form-group col-md-6 organization']
            ])
            ->add('organization_requisite_bik', 'text', [
                'label' => 'БИК',
                'wrapper' => ['class' => 'form-group col-md-6 organization']
            ])
            ->add('organization_requisite_city', 'text', [
                'label' => 'Город',
                'wrapper' => ['class' => 'form-group col-md-6 organization']
            ])
            ->add('organization_requisite_correspondent', 'text', [
                'label' => 'Корреспондентский счет',
                'wrapper' => ['class' => 'form-group col-md-6 organization']
            ])

            ->add('person_birthday', 'date', [
                'label' => 'Дата рождения',
                'wrapper' => ['class' => 'form-group col-md-6 person']
            ])
            ->add('person_document_phone', 'text', [
                'label' => 'Контактный телефон',
                'wrapper' => ['class' => 'form-group col-md-6 person']
            ])
            ->add('person_document_address', 'text', [
                'label' => 'Адрес регистрации',
                'wrapper' => ['class' => 'form-group col-md-12 person'],
                'attr' => ['max' => '26', 'placeholder' => 'Строка 1']
            ])
            ->add('address_line2', 'text', [
                'label' => 'Адрес регистрации [Линия 2]',
                'wrapper' => ['class' => 'form-group col-md-12 person'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 2']
            ])
            ->add('address_line3', 'text', [
                'label' => 'Адрес регистрации [Линия 3]',
                'wrapper' => ['class' => 'form-group col-md-12 person'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 3']
            ])
            ->add('document_title', 'static', [
                'tag' => 'h4',
                'wrapper' => ['class' => 'form-group col-md-12 person'],
                'attr' => ['class' => 'form-control-static person'],
                'value' => 'Документ',
                'label' => ''
            ])
            ->add('person_document', 'text', [
                'label' => 'Документ',
                'wrapper' => ['class' => 'form-group col-md-12 person']
            ])
            ->add('person_document_series', 'text', [
                'label' => 'Серия',
                'wrapper' => ['class' => 'form-group col-md-6 person']
            ])
            ->add('person_document_number', 'text', [
                'label' => 'Номер',
                'wrapper' => ['class' => 'form-group col-md-6 person']
            ])
            ->add('person_document_date', 'date', [
                'label' => 'Дата выдачи',
                'wrapper' => ['class' => 'form-group col-md-6 person']
            ])
            ->add('person_document_issuedby', 'text', [
                'label' => 'Кем выдан',
                'wrapper' => ['class' => 'form-group col-md-6 person']
            ])

            ->add('submit', 'submit', ['label' => 'Сохранить', 'attr' => ['class' => 'btn btn-success']])
            ->add('link', 'static', [
                'tag' => 'a',
                'attr' => ['class' => 'btn btn-default pull-left', 'href' => '/operator/clients/'],
                'value' => 'Отменить',
                'wrapper' => ['class' => 'pull-left ml15 mr10'],
                'label' => ''
            ]);
    }
}
