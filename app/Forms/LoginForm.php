<?php

namespace MMC\Forms;

use Kris\LaravelFormBuilder\Form;

class LoginForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('login', 'text', [
                'label' => 'Логин',
                'rules' => 'required'
            ])
            ->add('password', 'password', [
                'label' => 'Пароль',
                'rules' => 'required'
            ])
            ->add('submit', 'submit', ['label' => 'Войти', 'attr' => ['class' => 'btn btn-success']]);
    }
}
