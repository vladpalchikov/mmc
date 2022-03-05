<?php

namespace MMC\Forms;

use Kris\LaravelFormBuilder\Form;

class UpdateForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('version', 'text', [
                'label' => 'Версия', 
                'rules' => 'required'
            ])
            ->add('update', 'textarea', [
                'label' => 'Описание',
                'rules' => 'required',
                'attr' => ['class' => 'form-control editor']
            ])
            ->add('submit', 'submit', ['label' => 'Сохранить', 'attr' => ['class' => 'btn btn-success']]);
    }
}
