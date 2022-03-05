@extends('layouts.master')

@section('title', mb_strtoupper($client->name))

@section('menu')
    @include('partials.operator-menu', ['active' => 'clients'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h3 class="main-title main-title-single">
              {{ $client->name }}
              @if($client->is_host_only == 0)
                <span class="badge" style="margin-top: -4px; background-color: #f0ad4e">Представитель</span>
              @else
                <span class="badge" style="margin-top: -4px">Принимающая сторона</span>
              @endif
            </h3>
            <p>
              Создан: <strong class="mr10"> {{ $client->operator->name }} ({{ date('d.m.Y', strtotime($client->created_at)) }})</strong> Обновлен: <strong>{{ isset($client->updated_by) ? $client->operatorUpdated->name : $client->operator->name }} ({{ date('d.m.Y', strtotime($client->updated_at)) }})</strong><br>
              {{ $client->type == 0 ? 'Юридическое лицо' : 'Физическое лицо' }}, ИНН <strong class="mr10">{{ $client->inn }}</strong>
            </p>
        </div>
        <div class="col-md-6">
            @if(Auth()->user()->hasRole('admin|administrator|managermu|managermusn|managertm|managertmsn|accountant|chief.accountant'))
                <a href="/operator/clients/{{ $client->id }}/edit" class="btn btn-primary pull-right">Изменить данные</a>
                @if ($client->is_host_only == 0)
                  <a href="/operator/muservices/create?client_id={{ $client->id }}" class="btn btn-success pull-right mr10">Создать заявку</a>
                @endif
            @endif

            @if(Auth()->user()->hasRole('admin|administrator|accountant|chief.accountant') && $client->is_host_only == 0)
                <a role="button" id="add-balance" tabindex="0" data-trigger="focus" data-container="body" data-toggle="popover-balance" data-placement="bottom" class="btn btn-default pull-right text-info mr10">
                    Баланс: <strong>{{ number_format($client->getBalance(), 2, ',', ' ') }}</strong> руб.
                </a>
                <div id="popover-balance-content" class="hide">
                    <table class="c-table">
                        @foreach(\MMC\Models\Company::all() as $company)
                            <tr>
                                <td class="nbr">{{ $company->name }}</td>
                                <td class="c-sum nbr">
                                    <strong @if($company->getBalance($client) < 0) class="text-danger" @endif>
                                        {{ number_format($company->getBalance($client), 2, ',', ' ') }}
                                    </strong> руб.
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <a data-toggle="modal" data-target="#addBalance" class="btn btn-success btn-block mt10">Пополнить</a>
                    <a href="/operator/clients/{{ $client->id }}/transactions" class="btn btn-default btn-sm btn-block">Платежи ({{ $client->transactions->count() }})</a>
                </div>
                <button class="btn btn-link pull-right" disabled>Долг: <strong>{{ number_format($client->getDebts(), 0, ',', ' ') }}</strong> руб.</button>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @if ($client->type)
                <table class="table table-bordered">
                    <tr>
                        <th>Контактное&nbsp;лицо</th>
                        <th>Телефон</th>
                        <th>Email</th>
                        <th>Документ</th>
                        <th>Адрес регистрации</th>
                    </tr>

                    <tr>
                        <td>
                            @if ($client->type == 0)
                                {{ title_case($client->contact_person) }}
                            @else
                                {{ title_case($client->name) }}
                            @endif
                        </td>
                        <td>{{ $client->person_document_phone }}</td>
                        <td><a href="mailto:{{ $client->email }}" target="_blank">{{ $client->email }}</a></td>
                        <td>
                            @if ($client->person_document)
                                Документ <strong>{{ $client->person_document }}</strong> Серия <strong>{{ $client->person_document_series }}</strong> Номер <strong>{{ $client->person_document_number }}</strong> Выдан <strong>{{ $client->person_document_date }} {{ $client->person_document_issuedby }}</strong>
                            @endif
                        </td>
                        <td>
                          {{ $client->person_document_address }} {{ $client->address_line2 }} {{ $client->address_line3 }}
                        </td>
                    </tr>
                </table>
            @else
                <table class="table table-bordered mb5">
                    <tr>
                        <th>Контактное&nbsp;лицо</th>
                        <th>Телефон</th>
                        <th>Email</th>
                        <th>Наименование&nbsp;организации</th>
                        <th>ФИО&nbsp;директора</th>
                        <th>Юридический&nbsp;адрес / Регистрации</th>
                        <th>Реквизиты</th>
                    </tr>
                    <tr>
                        <td class="capitalize">{{ $client->contact_person }}</td>
                        <td>{{ $client->organization_contact_phone }}</td>
                        <td><a href="mailto:{{ $client->email }}" target="_blank">{{ $client->email }}</a></td>
                        <td>{{ $client->organization_fullname }}</td>
                        <td class="capitalize">{{ $client->organization_manager }}</td>
                        <td>{{ $client->organization_address }} {{ $client->organization_address_line2 }} {{ $client->organization_address_line3 }} </td>
                        <td>
                            ИНН <strong>{{ $client->organization_requisite_inn }}</strong> Р/С <strong>{{ $client->organization_requisite_account }}</strong>
                            <strong>{{ $client->organization_requisite_bank  }}</strong> <strong>{{ $client->organization_requisite_city  }}</strong> К/C
                            <strong>{{ $client->organization_requisite_correspondent }}</strong>
                            БИК <strong>{{ $client->organization_requisite_bik }}</strong>
                        </td>
                    </tr>
                </table>
            @endif
        </div>
    </div>

    @if ($client->transactions()->count() > 0 && Auth()->user()->hasRole('admin|administrator|managermusn|accountant|chief.accountant'))
        <div class="row mt20">
            <div class="col-md-12">
                <h3 class="pull-left main-title main-title-single">Последние платежи</h3>
            </div>
        </div>
        <div class="row mt10">
            <div class="col-md-12">
                <table class="table table-bordered mb5">
                    <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Сумма, руб.</th>
                            <th>Оператор</th>
                            <th>Номер платежного поручения</th>
                            <th>Комментарий</th>
                            <th>Бухгалтер</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($client->transactions()->limit(3)->get() as $transaction)
                            <tr>
                                <td>{{ date('d.m.Y H:i', strtotime($transaction->created_at)) }}</td>
                                <td>{{ number_format($transaction->sum, 0, ',', ' ') }}</td>
                                <td>
                                    @if ($transaction->company)
                                        {{ $transaction->company->name }}
                                    @endif
                                </td>
                                <td>{{ $transaction->number }}</td>
                                <td>{{ $transaction->comment }}</td>
                                <td>{{ $transaction->operator->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if ($client->transactions()->count() > 3)
            <a href="/operator/clients/{{ $client->id }}/transactions" class="btn btn-default btn-sm">Все транзакции ({{ $client->transactions->count() }})</a>
        @endif
    @endif

    @if($client->is_host_only == 0)
    <div class="row mt20">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Последние обращения</h3>
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a href="/operator/clients/{{ $client->id }}" class="btn btn-default @if (!Request::has('payment')) btn-primary @endif">Не оплаченные ({{ $countUnpayed }})</a>
                <a href="/operator/clients/{{ $client->id }}?payment=true" class="btn btn-default @if (Request::has('payment')) btn-primary @endif">Оплаченные ({{ $countPayed }})</a>
            </div>
        </div>
    </div>

    <div class="row mt10">
        <div class="col-md-12">
            <div class="btn-group" role="group" aria-label="...">
                <a href="/operator/clients/{{ $client->id }}?payment={{ Request::get('payment') }}&payment_method=" class="btn btn-default btn-sm @if (!Request::has('payment_method')) active @endif">Все ({{ $count }})</a>
                <a href="/operator/clients/{{ $client->id }}?payment={{ Request::get('payment') }}&payment_method=0" class="btn btn-default btn-sm @if (Request::get('payment_method') == 0 && Request::has('payment_method')) active @endif">Наличные ({{ $countMethodCash }})</a>
                <a href="/operator/clients/{{ $client->id }}?payment={{ Request::get('payment') }}&payment_method=1" class="btn btn-default btn-sm @if (Request::get('payment_method') == 1) active @endif">Безналичные ({{ $countMethodCashless }})</a>
            </div>
        </div>
    </div>
    @endif

    @if ($services->count() > 0 && $client->is_host_only == 0)
        <div class="row mt5">
            <div class="col-md-12">
                <table class="table table-bordered table-hover m-align mb5">
                    <tr>
                        <th>№</th>
                        <th>Услуга</th>
                        <th class="nbr">Цена, руб.</th>
                        <th>Количество</th>
                        <th class="nbr">Итого, руб.</th>
                        <th class="nbr">Дата оказания</th>
                        <th>Менеджер</th>
                        <th class="nbr">Способ оплаты</th>
                        <th class="nbr">Статус оплаты</th>
                        <th><abbr title="Пользователь подтвердивший оплату">Подтвердил</abbr></th>
                        <th>Оплата</th>
                        <th>Заявка</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                    @foreach ($services as $service)
                        @if (!$service->service_count)
                            @php
                                $service->service_count = 1;
                            @endphp
                        @endif
                        <tr @if ($service->repayment_status == 3) class="bg-danger" @endif @if ($service->payment_status == 0) class="bg-warning" @endif @if ($service->payment_status == 2) class="service-archive text-muted" @endif>
                            <td class="nbr">
                                @if (isset($service->is_mu))
                                    <a href="/operator/foreigners/{{ $service->foreigner->id }}">{{ $service->id }}</a>
                                @else
                                    <a href="/operator/group/{{ $service->id }}">{{ $service->id }}</a>
                                @endif
                            </td>
                            <td class="nbr">{{ $service->service_name }}</td>
                            <td class="nbr" style="text-align: right">{{ round($service->service_price) }}</td>
                            <td class="nbr" style="text-align: right">{{ $service->service_count }}</td>
                            <td class="nbr" style="text-align: right">{{ number_format($service->service_count * $service->service_price, 0, ',', ' ') }}</td>
                            <td class="nbr">{{ date('d.m.Y H:i', strtotime($service->created_at)) }}</td>
                            <td class="nbr">
                                @if ($service->operator)
                                    {{ $service->operator->name }}
                                @endif
                            </td>
                            <td class="nbr">{{ $service->payment_method == 0 ? 'Наличными в кассу' : 'Безналичная оплата' }}</td>
                            <td>
                                @if(Auth::user()->hasRole('administrator|managermu|managermusn|cashier|accountant|chief.accountant|business.manager'))
                                    @if ($service->payment_status == 0)
                                        &mdash;
                                    @elseif ($service->payment_status == 2)
                                        <span class="text-muted nbr">Архивирована</span>
                                    @elseif ($service->payment_status == 1)
                                        <span class="text-success">{{ date('d.m.Y H:i', strtotime($service->payment_at)) }}</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if (isset($service->cashier_id))
                                    {{ \MMC\Models\User::find($service->cashier_id)->name }}
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td>
                                @if ($service->payment_status == 0)

                                    @if($service->payment_method == 1)
                                        @if ($service->service_price * $service->service_count > $service->service->company->getBalance($service->client))
                                            <button class="btn btn-default btn-block btn-sm" title="Стоимость: {{ $service->service_price * $service->service_count }} Баланс: {{ $service->service->company->getBalance($service->client) }} Код: 1" disabled>Подтвердить</a>
                                        @else
                                            @if (isset($service->is_mu) && Auth::user()->hasRole('administrator|accountant|chief.accountant|managertm|managertmsn'))
                                                <a href="/operator/foreigners/{{ $service->id }}/servicepay" class="btn btn-success btn-sm btn-block">Подтвердить</a>
                                            @elseif (!isset($service->is_mu) && Auth::user()->hasRole('administrator|accountant|chief.accountant|managermu|managermusn'))
                                                <a href="/operator/muservices/{{ $service->id }}/servicepay" class="btn btn-success btn-sm btn-block">Подтвердить</a>
                                            @else
                                                <button class="btn btn-default btn-block btn-sm" title="Стоимость: {{ $service->service_price * $service->service_count }} Баланс: {{ $service->service->company->getBalance($service->client) }} Код: 2" disabled>Подтвердить</button>
                                            @endif
                                        @endif
                                    @else
                                        @if (isset($service->is_mu) && Auth::user()->hasRole('administrator|accountant|chief.accountant'))
                                            <a href="/operator/foreigners/{{ $service->id }}/servicepay" class="btn btn-success btn-sm btn-block">Подтвердить</a>
                                        @elseif (!isset($service->is_mu) && Auth::user()->hasRole('administrator|accountant|chief.accountant'))
                                            <a href="/operator/muservices/{{ $service->id }}/servicepay" class="btn btn-success btn-sm btn-block">Подтвердить</a>
                                        @else
                                            <button class="btn btn-default btn-block btn-sm" title="Код: 3" disabled>Подтвердить</button>
                                        @endif
                                    @endif

                                @elseif ($service->payment_status == 1)
                                    @if (Auth::user()->hasRole('administrator'))
                                        <a href="/operator/muservices/{{ $service->id }}/servicepay" class="btn btn-danger btn-sm btn-block @if (isset($service->is_mu)) disabled @endif">Отменить</a>
                                    @else
                                        <button class="btn btn-danger btn-sm btn-block" disabled>Отменить</button>
                                    @endif
                                @elseif ($service->payment_status == 2)
                                    <a href="/operator/muservices/{{ $service->id }}/servicepay" class="btn btn-default btn-sm btn-block @if (isset($service->is_mu)) disabled @endif">Активировать</a>
                                @endif
                            </td>
                            <td>
                                @if ($service->is_mu !== 0)
                                    <a href="/operator/muservices/{{ $service->id }}/print" class="btn btn-default btn-sm btn-block">Распечатать</a>
                                @else
                                    <a class="btn btn-default btn-sm btn-block" href="/operator/foreigners/{{ $service->id }}/print">Распечатать</a>
                                @endif
                            </td>
                            <td>
                                @if ($service->payment_status == 0 && $service->is_mu !== 0 && Auth::user()->hasRole('administrator|managermu|managermusn'))
                                    <a href="/operator/muservices/{{ $service->id }}/edit" class="btn btn-primary btn-sm btn-block">
                                        Редактировать
                                    </a>
                                @else
                                    <button class="btn btn-default btn-sm btn-block" disabled="disabled">Редактировать</button>
                                @endif
                            </td>
                            <td>
                                @if ($service->payment_status == 0 && (\Carbon\Carbon::parse($service->created_at)->diffInHours(\Carbon\Carbon::now()) < 24 ))
                                    <a href="/operator/muservices/{{ $service->id }}" class="btn btn-danger btn-sm ajax-delete btn-block @if (isset($service->is_mu)) disabled @endif">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </a>
                                @else
                                    <button class="btn btn-default btn-sm btn-block" disabled="disabled">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <a href="/operator/clients/{{ $client->id }}/all" class="btn btn-default btn-sm">Все обращения ({{ $countAll }})</a>
    @else
      @if($client->is_host_only == 0)
        <div class="row">
            <div class="col-md-12">
            @if (Request::has('payment'))
                <div class="alert alert-info text-center mt20">Оплаченных услуг нет</div>
            @else
                <div class="alert alert-info text-center mt20">Все услуги оплачены</div>
            @endif
            </div>
        </div>
        @endif
    @endif

    @include('modals.add-balance-client-modal')
@endsection
