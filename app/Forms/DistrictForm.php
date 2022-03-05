<?php

namespace MMC\Forms;

use Kris\LaravelFormBuilder\Form;

class DistrictForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('oktmo', 'text', [
                'label' => 'ОКТМО', 
                'rules' => 'required'
            ])
            ->add('district', 'text', [
                'label' => 'Район',
                'rules' => 'required'
            ])
            ->add('ifns', 'text', [
                'label' => 'ИФНС',
                'rules' => 'required'
            ])
            ->add('submit', 'submit', ['label' => 'Сохранить', 'attr' => ['class' => 'btn btn-success']]);
    }
}
