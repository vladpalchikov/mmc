<?php

namespace MMC\Forms;

use Kris\LaravelFormBuilder\Form;

class MMCForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('name', 'text', [
                'label' => 'Название',
                'rules' => 'required'
            ])
            ->add('title', 'text', [
                'label' => 'Заголовок для документов',
                'rules' => 'required'
            ])
            ->add('address', 'text', [
                'label' => 'Адрес',
                'rules' => 'required'
            ])
            ->add('city_code', 'text', [
                'label' => 'Код города',
                'rules' => 'required'
            ])
            ->add('ip', 'text', [
                'label' => 'Ограничение по IP адресу (несколько через запятую)',
            ])
            ->add('submit', 'submit', ['label' => 'Сохранить', 'attr' => ['class' => 'btn btn-success']])
            ->add('link', 'static', [
                'tag' => 'a',
                'attr' => ['class' => 'btn btn-default pull-left ', 'href' => '/admin/mmc'],
                'value' => 'Отменить',
                'wrapper' => ['class' => 'pull-left mr10'],
                'label' => ''
            ]);
    }
}
