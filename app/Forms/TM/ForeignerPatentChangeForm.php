<?php

namespace MMC\Forms\TM;

use Kris\LaravelFormBuilder\Form;

class ForeignerPatentChangeForm extends Form
{
    public function buildForm()
    {
        $data = $this->getData();
        $this
            ->add('reason', 'choice', [
                'label' => 'Изменения вносятся в связи с',
                'choices' => [
                    0 => 'изменением фамилии, имени или отчества (при наличии)',
                    1 => 'изменением реквизитов документа, удостоверяющего личность',
                    2 => 'изменением профессии (специальности, должности, вида трудовой деятельности)',
                ],
                'expanded' => true,
                'label_attr' => ['class' => 'choices-label'],
                'wrapper' => ['class' => 'form-group col-md-12 choice-group']
            ])
	        ->add('surname_change', 'text', [
	            'label' => 'Фамилия',
	            'wrapper' => ['class' => 'form-group col-md-4'],
	            'attr' => ['max' => '37', 'autofocus' => 'autofocus']
	        ])
	        ->add('name_change', 'text', [
                'label' => 'Имя',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '37']
            ])
            ->add('middle_name_change', 'text', [
                'label' => 'Отчество',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '37']
            ])

            ->add('document_name_change', 'text', [
                'label' => 'Документ, удостоверяющий личность',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '26']
            ])
            ->add('document_series_change', 'text', [
                'label' => 'Серия',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '7']
            ])
            ->add('document_number_change', 'text', [
                'label' => 'Номер',
                'wrapper' => ['class' => 'form-group col-md-4'],
                'attr' => ['max' => '11']
            ])
            ->add('document_date_change', 'date', [
                'label' => 'Дата выдачи',
                'wrapper' => ['class' => 'form-group col-md-4'],
            ])
            ->add('document_issued_change', 'text', [
                'label' => 'Кем выдан',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '37']
            ])

            ->add('patent_series_change', 'text', [
                'label' => 'Патент: Серия',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'attr' => ['max' => '6']
            ])
             ->add('patent_number_change', 'text', [
                'label' => 'Патент: Номер',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'attr' => ['max' => '15']
            ])

            ->add('blank_patent_series_change', 'text', [
                'label' => 'Бланк патента: Серия',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'attr' => ['max' => '6']
            ])
            ->add('blank_patent_number_change', 'text', [
                'label' => 'Бланк патента: Номер',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'attr' => ['max' => '15']
            ])

            ->add('date_change', 'date', [
                'label' => 'Дата выдачи',
                'wrapper' => ['class' => 'form-group col-md-4'],
            ])

            ->add('profession_change', 'text', [
                'label' => 'Профессия (специальность, должность, вид трудовой деятельности), по которой планируется
осуществление трудовой деятельности',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '23', 'placeholder' => 'Строка 1']
            ])
            ->add('profession_line2_change', 'text', [
                'label' => 'Профессия (специальность, должность, вид трудовой деятельности), по которой планируется
осуществление трудовой деятельности [Строка 2]',
                'wrapper' => ['class' => 'form-group col-md-12'],
                'attr' => ['max' => '42', 'placeholder' => 'Строка 2'],
                'label_attr' => ['class' => 'hide']
            ])

            ->add('submit', 'submit', ['label' => 'Сохранить', 'attr' => ['class' => 'btn btn-success pull-left ml15']])
            ->add('link', 'static', [
                'tag' => 'a',
                'attr' => ['class' => 'btn btn-default pull-left ml15', 'href' => '/operator/foreigners/'.$data['id']],
                'value' => 'Отменить',
                'wrapper' => ['class' => 'pull-left mr10'],
                'label' => ''
            ]);
	    ;

        $patent_change_id = $this->getModel() ? $this->getModel()->id : '';
    }
}
