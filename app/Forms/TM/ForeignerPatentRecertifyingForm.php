<?php

namespace MMC\Forms\TM;

use Kris\LaravelFormBuilder\Form;

class ForeignerPatentRecertifyingForm extends Form
{
    public function buildForm()
    {
        $data = $this->getData();

        $patent_id = $this->getModel() ? $this->getModel()->id : '';
        $this
            ->add('surname', 'text', [
                'label' => 'Фамилия',
                'default_value' => $data['surname'],
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '37', 'autofocus' => 'autofocus']
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
            ->add('name_change', 'text', [
                'label' => 'Сведения об изменении Ф.И.О.',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '70'],
                'value' => $this->getModel() ? $this->getModel()->name_change : 'НЕ МЕНЯЛ'
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
            ->add('birthday_place', 'text', [
                'label' => 'Место рождения',
                'wrapper' => ['class' => 'form-group col-md-8'],
                'attr' => ['max' => '35']
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
            ->add('registration_address', 'text', [
                'label' => 'Адрес постоянного проживания',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '31', 'placeholder' => 'Строка 1']
            ])
            ->add('registration_address_line2', 'text', [
                'label' => 'Адрес постоянного проживания [Строка 2]',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 2'],
                'label_attr' => ['class' => 'hide']
            ])
            ->add('document_name', 'text', [
                'label' => 'Документ, удостоверяющий личность',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'default_value' => $data['document_name'],
                'attr' => ['max' => '26']
            ])
            ->add('document_series', 'text', [
                'label' => 'Серия',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'default_value' => $data['document_series'],
                'attr' => ['max' => '7']
            ])
            ->add('document_number', 'text', [
                'label' => 'Номер',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'default_value' => $data['document_number'],
                'attr' => ['max' => '11']
            ])
            ->add('document_date', 'date', [
                'label' => 'Дата выдачи',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'default_value' => $data['document_date']
            ])
            ->add('document_organization', 'text', [
                'label' => 'Кем выдан',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '37']
            ])
            ->add('migration_card_number', 'text', [
                'label' => 'Номер миграционной карты',
                'wrapper' => ['class' => 'form-group col-md-8'],
                'attr' => ['max' => '13']
            ])
            ->add('migration_card_date', 'date', [
                'label' => 'Дата выдачи',
                'wrapper' => ['class' => 'form-group col-md-4']
            ])
            ->add('address', 'text', [
                'label' => 'Адрес постановки на учет по месту пребывания',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'default_value' => $data['address'],
                'attr' => ['max' => '26']
            ])
            ->add('address_line2', 'text', [
                'label' => 'Адрес постановки на учет по месту пребывания [Строка 2]',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'default_value' => $data['address_line2'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 2'],
                'label_attr' => ['class' => 'hide']
            ])
            ->add('address_line3', 'text', [
                'label' => 'Адрес постановки на учет по месту пребывания [Строка 3]',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'default_value' => $data['address_line3'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 3'],
                'label_attr' => ['class' => 'hide']
            ])
            ->add('registration_date_from', 'date', [
                'label' => 'с',
                'wrapper' => ['class' => 'form-group col-md-6']
            ])
            ->add('registration_date_to', 'date', [
                'label' => 'по',
                'wrapper' => ['class' => 'form-group col-md-6']
            ])
            ->add('inn', 'text', [
                'label' => 'ИНН (при наличии)',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'attr' => ['max' => '16'],
                'value' => $data['inn'],
            ])
            ->add('russian_document', 'choice', [
                'label' => 'Документ, подтверждающий владение русским языком, знание истории России и основ законодательства Российской Федерации',
                'wrapper' => ['class' => 'form-group col-md-12 choice-group'],
                'label_attr' => ['class' => 'choices-label'],
                'choices' => [
                    'сертификат' => 'сертификат',
                    'аттестат' => 'аттестат',
                    'диплом' => 'диплом',
                    'свидетельство' => 'свидетельство'
                ]
            ])
            ->add('russian_document_line2', 'text', [
                'label' => 'Документ, подтверждающий владение русским языком, знание истории России и основ законодательства Российской Федерации [Строка 2]',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'label_attr' => ['class' => 'hide'],
                'attr' => ['max' => '43', 'placeholder' => 'Строка 2']
            ])
            ->add('russian_series', 'text', [
                'label' => 'Серия',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '7']
            ])
            ->add('russian_number', 'text', [
                'label' => 'Номер',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '12']
            ])
            ->add('russian_date', 'date', [
                'label' => 'Дата выдачи',
                'wrapper' => ['class' => 'form-group col-md-4']
            ])
            ->add('work_activity', 'choice', [
                'label' => 'Трудовая деятельность планируется у',
                'choices' => ['юридического лица или индивидуального предпринимателя (абзац первый пункта 1 статьи 133 Федерального
закона от 25 июля 2002 г. № 115-ФЗ «О правовом положении иностранных граждан в Российской Федерации»)', 'физического лица – гражданина Российской Федерации (абзац второй пункта 1 статьи 133 Федерального закона от
25 июля 2002 г. № 115-ФЗ «О правовом положении иностранных граждан в Российской Федерации»)'],
                'expanded' => true,
                'selected' => $this->getModel() ? [$this->getModel()->work_activity] : [0],
                'label_attr' => ['class' => 'choices-label'],
                'wrapper' => ['class' => 'form-group col-md-12 choice-group']
            ])
            ->add('profession', 'text', [
                'label' => 'Профессия (специальность, должность, вид трудовой деятельности), по которой планируется
осуществление трудовой деятельности',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '23', 'placeholder' => 'Строка 1']
            ])
            ->add('profession_line2', 'text', [
                'label' => 'Профессия (специальность, должность, вид трудовой деятельности), по которой планируется
осуществление трудовой деятельности [Строка 2]',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 2'],
                'label_attr' => ['class' => 'hide']
            ])
            ->add('work_until', 'date', [
                'label' => 'Предполагаемый срок осуществления трудовой деятельности в Российской Федерации',
                'wrapper' => ['class' => 'form-group col-md-12']
            ])
            ->add('prev_patent', 'text', [
                'label' => 'Патент выдан',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '33', 'placeholder' => 'Строка 1']
            ])
            ->add('prev_patent_line2', 'text', [
                'label' => 'Патент выдан [Строка 2]',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 2'],
                'label_attr' => ['class' => 'hide']
            ])
            ->add('prev_patent_series', 'text', [
                'label' => 'Патент: серия',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'attr' => ['max' => '6']
            ])
            ->add('prev_patent_number', 'text', [
                'label' => 'Патент: №',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'attr' => ['max' => '15']
            ])
            ->add('prev_patent_blank_series', 'text', [
                'label' => 'Бланк патента: серия',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'attr' => ['max' => '6']
            ])
            ->add('prev_patent_blank_number', 'text', [
                'label' => 'Бланк патента: №',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'attr' => ['max' => '15']
            ])
            ->add('prev_patent_date_from', 'date', [
                'label' => 'Срок действия с',
                'wrapper' => ['class' => 'form-group col-md-6']
            ])
            ->add('prev_patent_date_to', 'date', [
                'label' => 'Срок действия по',
                'wrapper' => ['class' => 'form-group col-md-6']
            ])
            ->add('application_from', 'choice', [
                'label' => 'Заявление подается',
                'choices' => [
                    0 => 'лично', 
                    // 1 => 'через лицо, выступающее в соответствии с гражданским законодательством Российский Федерации в качестве представителя иностранного гражданина',
                    2 => 'через уполномоченную субъектом Российской Федерации организацию'
                ],
                'selected' => 0,
                'expanded' => true,
                'label_attr' => ['class' => 'choices-label'],
                'wrapper' => ['class' => 'form-group col-md-12 choice-group']
            ])
			->add('submit', 'submit', ['label' => 'Сохранить', 'attr' => ['class' => 'btn btn-success pull-left ml15']])
            ->add('link', 'static', [
                'tag' => 'a',
                'attr' => ['class' => 'btn btn-default pull-left ml15', 'href' => '/operator/foreigners/'.$data['id']],
                'value' => 'Отменить',
                'wrapper' => ['class' => 'pull-left mr10'],
                'label' => ''
            ]);

    }
}
