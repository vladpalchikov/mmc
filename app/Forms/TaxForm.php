<?php

namespace MMC\Forms;

use Kris\LaravelFormBuilder\Form;

class TaxForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('code', 'text', [
                'label' => 'Код', 
                'rules' => 'required'
            ])
            ->add('name', 'text', [
                'label' => 'Название', 
                'rules' => 'required'
            ])
            ->add('comment', 'textarea', [
                'label' => 'Комментарий', 
                'rules' => 'required'
            ])
            ->add('price', 'text', [
                'label' => 'Цена', 
                'rules' => 'required'
            ])
            ->add('provider', 'text', [
                'label' => 'Провайдер', 
                'rules' => 'required'
            ])
            ->add('submit', 'submit', ['label' => 'Сохранить', 'attr' => ['class' => 'btn btn-success']]);
    }
}
