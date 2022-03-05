<?php

namespace MMC\Forms;

use Kris\LaravelFormBuilder\Form;
use Ultraware\Roles\Models\Role;
use Ultraware\Roles\Models\Permission;
use Auth;

class UserForm extends Form
{
    public function buildForm()
    {
        $roles = [];
        $rolesQuery = new Role;
        if ($this->getModel()) {
            $user = \MMC\Models\User::find($this->getModel()->id);
        }

        if (Auth::user()->hasRole('business.manager')) {
            $rolesQuery = $rolesQuery->where('slug', '<>', 'admin')->where('slug', '<>', 'administrator')->where('slug', '<>', 'chief.accountant')->where('slug', '<>', 'business.manager');
        } else {
            $rolesQuery = $rolesQuery->where('slug', '<>', 'admin');
        }

        foreach ($rolesQuery->get() as $role) {
            $roles[$role->id] = $role->name;
        }

        $role = 0;
        $selectedRoles = [];
        if ($this->getModel()) {
            if ($user->getRoles()->count() > 0) {
                foreach ($user->getRoles() as $userRole) {
                    $selectedRoles[] = $userRole->id;
                }
            }
        }

        foreach (Permission::all() as $permission) {
            $permissions[$permission->id] = $permission->name;
        }

        $permission = 0;
        $selectedPermission = [];
        if ($this->getModel()) {
            if ($user->getPermissions()->count() > 0) {
                foreach ($user->getPermissions() as $userPermission) {
                    $selectedPermission[] = $userPermission->id;
                }
            }
        }

        $selectedMMC = [];
        if ($this->getModel()) {
            if ($user->mmcList()->count() > 0) {
                foreach ($user->mmcList as $mmc) {
                    $selectedMMC[] = $mmc->mmc_id;
                }
            }
        }

        $mmc = [];

        if (Auth::user()->hasRole('business.manager')) {
            $mmc[Auth::user()->mmc->id] = Auth::user()->mmc->name;
        } else {
            foreach (\MMC\Models\MMC::all() as $item) {
                $mmc[$item->id] = $item->name;
            }
        }

        $this
            ->add('name', 'text', [
                'label' => 'ФИО',
                'rules' => 'required'
            ])
            ->add('phone', 'text', [
                'label' => 'Телефон',
            ])
            ->add('role', 'choice', [
                'label' => 'Роль',
                'choices' => $roles,
                'expanded' => true,
                'multiple' => true,
                'selected' => $selectedRoles,
                'label_attr' => ['class' => 'choices-label'],
                'wrapper' => ['class' => 'form-group choice-group form-group col-md-12 pl0']
            ])
            ->add('permission', 'choice', [
                'label' => 'Доступ к модулям',
                'choices' => $permissions,
                'expanded' => true,
                'multiple' => true,
                'selected' => $selectedPermission,
                'label_attr' => ['class' => 'choices-label'],
                'wrapper' => ['class' => 'form-group choice-group form-group col-md-12 pl0']
            ])
            ->add('mmc_id', 'choice', [
                'label' => 'Миграционный центр',
                'choices' => $mmc,
                'expanded' => true,
                'multiple' => true,
                'selected' => $selectedMMC,
                'label_attr' => ['class' => 'choices-label'],
                'wrapper' => ['class' => 'form-group choice-group form-group col-md-12 pl0']
            ])
            ->add('active', 'checkbox', [
            	'label' => 'Активен',
			    'value' => $this->getModel() ? $this->getModel()->active : true,
                'checked' => $this->getModel() ? $this->getModel()->active : true
			])
            ->add('is_have_access_strict_report', 'checkbox', [
                'label' => 'Доступ к бланкам строгой отчетности',
                'value' => $this->getModel() ? $this->getModel()->is_have_access_strict_report : true,
                'checked' => $this->getModel() ? $this->getModel()->is_have_access_strict_report : true
            ])
            ->add('is_have_access_registry', 'checkbox', [
                'label' => 'Доступ к формированию реестра',
                'value' => $this->getModel() ? $this->getModel()->is_have_access_registry : true,
                'checked' => $this->getModel() ? $this->getModel()->is_have_access_registry : true
            ])
            ->add('entry_data', 'checkbox', [
                'label' => 'Изменить данные входа',
                'checked' => $this->getModel() ? false : true
            ])
            ->add('login', 'text', [
                'label' => 'Логин',
                'wrapper' => ['class' => 'form-group entry-data']
            ])
            ->add('password', 'password', [
                'label' => 'Пароль',
                'wrapper' => ['autocomplete' => 'off', 'class' => 'form-group entry-data']
            ])
            ->add('submit', 'submit', ['label' => 'Сохранить', 'attr' => ['class' => 'btn btn-success']])

            ->add('link', 'static', [
                'tag' => 'a',
                'attr' => ['class' => 'btn btn-default pull-left ', 'href' => '/users'],
                'value' => 'Отменить',
                'wrapper' => ['class' => 'pull-left mr10'],
                'label' => ''
            ]);
    }
}
