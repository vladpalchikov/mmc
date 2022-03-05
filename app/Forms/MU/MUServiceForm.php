<?php

namespace MMC\Forms\MU;

use Kris\LaravelFormBuilder\Form;
use MMC\Models\Client;

class MUServiceForm extends Form
{
	public function buildForm()
    {
        $clients[null] = 'Выберите плательщика';
        foreach (Client::orderBy('name')->get()->where('is_host_only', '=', '0') as $client) {
            if ($client->inn) {
                $clients[$client->id] = $client->name .' ('.$client->inn.')';
            } else {
                $clients[$client->id] = $client->name;
            }
        }

        $data = $this->getData();

        $this
			->add('created_at', 'date', [
				'label' => 'Запланировать выполнение - Дата',
				'wrapper' => ['class' => 'form-group col-md-4'],
				'value' => $this->getModel() ? date('Y-m-d', strtotime($this->getModel()->created_at)) : date('Y-m-d'),
                'attr' => $this->getModel() ? ['disabled' => 'disabled'] : []
			])
            ->add('created_at_time', 'time', [
                'label' => 'Запланировать выполнение - Время',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'value' => $this->getModel() ? date('H:i:s', strtotime($this->getModel()->created_at)) : date('H:i:s'),
                'attr' => $this->getModel() ? ['disabled' => 'disabled'] : []
            ])
            ->add('client_id', 'choice', [
                'label' => 'Плательщик (представитель)',
                'choices' => $clients,
                'rules' => 'required',
                'selected' => isset($data['client_id']) ? $data['client_id'] : ($this->getModel() ? [$this->getModel()->client_id] : [0]),
                'label_attr' => ['class' => 'choices-label'],
                'wrapper' => ['class' => 'form-group col-md-9 pl0'],
            ])
            ->add('payment_method', 'choice', [
                'choices' => ['Наличными в кассу', 'Безналичная оплата'],
                'choice_options' => [
                    'wrapper' => ['class' => 'choice-wrapper'],
                    'label_attr' => ['class' => 'label-class'],
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Способ оплаты',
                'label_attr' => ['class' => 'choices-label'],
                'selected' => $this->getModel() ? [$this->getModel()->payment_method] : [0],
                'wrapper' => ['class' => 'form-group choice-group form-group']
            ])
            ->add('submit', 'submit', [
                'label' => 'Сохранить',
                'attr' => ['class' => 'btn btn-success pull-left']
            ])
            ->add('link', 'static', [
                'tag' => 'a',
                'attr' => ['class' => 'btn btn-default pull-left ml15', 'href' => '/operator/muservices/'],
                'value' => 'Отменить',
                'wrapper' => ['class' => 'pull-left mr10'],
                'label' => ''
            ]);
        ;
    }
}
