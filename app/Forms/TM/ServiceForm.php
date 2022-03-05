<?php

namespace MMC\Forms\TM;

use Kris\LaravelFormBuilder\Form;

class ServiceForm extends Form
{
    public function buildForm()
    {
        $companies = [];
        $taxes = [];

        foreach (\MMC\Models\Company::all() as $company) {
            $companies[$company->id] = $company->name;
        }

        $taxes[] = 'Без налога';
        foreach (\MMC\Models\Tax::all() as $tax) {
            $taxes[$tax->id] = $tax->name;
        }

        $this
            ->add('price', 'text', [
                'label' => 'Цена (руб)',
                'rules' => 'required|numeric',
                'value' => $this->getModel() ? round($this->getModel()->price) : 0,
            ])
            ->add('description', 'textarea', [
                'label' => 'Наименование услуги (для заявки)',
                'rules' => 'required'
            ])
            ->add('service_included', 'textarea', [
                'label' => 'Услуга включает (для заявки)'
            ])
            ->add('name', 'text', [
                'label' => 'Краткое название (для отчета)',
                'rules' => 'required'
            ])
            ->add('agent_compensation', 'text', [
                'label' => 'Агентское вознаграждение',
                'rules' => 'numeric',
                'value' => $this->getModel() ? round($this->getModel()->agent_compensation) : 0,
            ])
            ->add('principal_sum', 'text', [
                'label' => 'Сумма принципалу',
                'rules' => 'numeric',
                'value' => $this->getModel() ? round($this->getModel()->principal_sum) : 0,
            ])
            ->add('order', 'text', [
                'label' => 'Порядковый номер',
                'rules' => 'required',
                'default_value' => 0
            ])
            ->add('company_id', 'choice', [
                'label' => 'Оператор',
                'rules' => 'required',
                'choices' => $companies,
                'selected' => $this->getModel() ? [$this->getModel()->company_id] : 0
            ])
            ->add('tax_id', 'choice', [
                'label' => 'Налог',
                'choices' => $taxes,
                'selected' => $this->getModel() ? [$this->getModel()->tax_id] : 0
            ])
            ->add('module', 'choice', [
                'label' => 'Модуль',
                'rules' => 'required',
                'choices' => ['Трудовая миграция', 'Миграционный учет', 'Блок Гражданство'],
                'selected' => $this->getModel() ? [$this->getModel()->module] : 0,
            ])
            ->add('status', 'checkbox', [
            	'label' => 'Доступен для оформления',
			    'value' => $this->getModel() ? $this->getModel()->status : true,
			    'checked' => $this->getModel() ? $this->getModel()->status : true
			])
            ->add('labor_exchange', 'checkbox', [
                'label' => 'Биржа труда',
                'value' => $this->getModel() ? $this->getModel()->labor_exchange : true,
                'checked' => $this->getModel() ? $this->getModel()->labor_exchange : true
            ])
            ->add('is_complex', 'checkbox', [
                'label' => 'Комплексная услуга',
                'value' => $this->getModel() ? $this->getModel()->is_complex : false,
                'checked' => $this->getModel() ? $this->getModel()->is_complex : false
            ])
            ->add('submit', 'submit', ['label' => 'Сохранить', 'attr' => ['class' => 'btn btn-success']])
            ->add('link', 'static', [
                'tag' => 'a',
                'attr' => ['class' => 'btn btn-default pull-left ', 'href' => '/services'],
                'value' => 'Отменить',
                'wrapper' => ['class' => 'pull-left mr10'],
                'label' => ''
            ]);
    }
}
