@extends('layouts.master')


@section('title', title_case($foreigner->surname.' '.$foreigner->name.' '.$foreigner->middle_name))

@section('menu')
    @if(Auth()->user()->hasRole('admin'))
        @include('partials.admin-menu', ['active' => 'foreigners'])
    @else
        @include('partials.operator-menu', ['active' => 'foreigners'])
    @endif
@endsection

@section('content')
@if ($foreigner->registration_date < date('Y-m-d') || !isset($foreigner->inn))
<div class="row well mb5">
    <div class="alert alert-warning text-center mb0" style="margin-top: -21px; border-radius: 0" role="alert">
        <p><strong>Данные устарели или не полные.</strong> Удостоверьтесь в актуальности данных гражданина перед оказанием услуг, обязательно проверьте наличие <strong>ИНН</strong>, <strong>адрес и дату окончания регистрации</strong> гражданина.</p>
    </div>
</div>
@endif
	<div class="row">
		<div class="col-md-6">
            <h3 class="main-compare" data-history-main="name">{{ title_case($foreigner->surname.' '.$foreigner->name.' '.$foreigner->middle_name) }}</h3>
            <p>
                @if ($foreigner->operator)
                    Создан: <strong class="mr10">{{ $foreigner->operator->name }} ({{ date('d.m.Y', strtotime($foreigner->created_at)) }})</strong>Обновлен: <strong>{{ isset($foreigner->updated_by) ? $foreigner->updatedByUser->name : $foreigner->operator->name }} ({{ date('d.m.Y', strtotime($foreigner->updated_at)) }})</strong><br>
                @else
                    Создан: <strong class="mr10">Неизвестный менеджер ({{ date('d.m.Y', strtotime($foreigner->created_at)) }})</strong>Обновлен: <strong>{{ isset($foreigner->updated_by) ? $foreigner->updatedByUser->name : 'Неизвестный менеджер' }} ({{ date('d.m.Y', strtotime($foreigner->updated_at)) }})</strong><br>
                @endif

                ИНН
                @if ($foreigner->inn == 0 && !$foreigner->inn_check)
                    <span class="text-danger mr10 main-compare" data-history-main="inn">
                      <strong>{{ isset($foreigner->inn) ? $foreigner->inn : '0' }}</strong>
                    </span>
                @else
                    <strong class="mr10 main-compare" data-history-main="inn">
                      {{ isset($foreigner->inn) ? $foreigner->inn : '0' }}
                    </strong>
                @endif

                ОКТМО
                @if ($foreigner->oktmo_fail)
                    <span class="text-danger mr10 main-compare" data-history-main="oktmo" title="ОКТМО введен вручную">
                      <strong>{{ isset($foreigner->oktmo) ? $foreigner->oktmo : '0' }}</strong>
                    </span>
                @else
                    <strong class="mr10 main-compare" data-history-main="oktmo">
                      {{ isset($foreigner->oktmo) ? $foreigner->oktmo : '0' }}
                    </strong>
                @endif

                Регистрация до
                @if ($foreigner->registration_date < date('Y-m-d'))
                    <span class="text-danger mr10 main-compare" data-history-main="registration_date" title="Срок регистрации истек">
                        <strong>{{ isset($foreigner->registration_date) ? date('d.m.Y', strtotime($foreigner->registration_date)) : '&mdash;' }}</strong>
                    </span>
                @else
                    <strong class="mr10 main-compare" data-history-main="registration_date">{{ isset($foreigner->registration_date) ? date('d.m.Y', strtotime($foreigner->registration_date)) : '&mdash;' }}</strong>
                @endif
            </p>
        </div>
        <div class="col-md-6">
            @if(Auth::user()->hasRole('admin|administrator|managertm|managerbg|managertmsn|managermu|managermusn'))
                <a href="/operator/foreigners/{{ $foreigner->id }}/edit" class="btn btn-primary pull-right mt25">Изменить данные</a>
            @endif
        </div>
	  </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-10px-mb">
                <tr>
                    <th>Гражданство</th>
                    <th>Документ</th>
                    <th>Серия</th>
                    <th>Номер</th>
                    <th>Выдан</th>
                    <th>Адрес&nbsp;регистрации</th>
                    <th>Принимающая&nbsp;сторона</th>
                    <th>Телефон</th>
                    <th>Пол</th>
                    <th>Дата&nbsp;рождения</th>
                </tr>
                <tr>
                    <td class="capitalize main-compare" data-history-main="nationality">
                        {{ $foreigner->nationality }} <br>
                        {{ $foreigner->nationality_line2 }}
                    </td>
                    <td class="capitalize main-compare" data-history-main="document_name">{{ $foreigner->document_name }}</td>
                    <td class="uppercase main-compare" data-history-main="document_series">
                        @if (!empty($foreigner->document_series))
                            <nobr>{{ $foreigner->document_series }}</nobr>
                        @else
                            &mdash;
                        @endif
                    </td>
                    <td class="main-compare" data-history-main="document_number">{{ $foreigner->document_number }}</td>
                    <td class="main-compare" data-history-main="document_issuedby"><nobr>{{ isset($foreigner->document_date) ? date('d.m.Y', strtotime($foreigner->document_date)) : '&mdash;' }}</nobr> {{ $foreigner->document_issuedby }}</td>
                    <td class="capitalize main-compare" data-history-main="address">{{ $foreigner->address }} {{ $foreigner->address_line2 }} {{ $foreigner->address_line3 }}</td>
                    <td class="capitalize main-compare" data-history-main="host">
                        @if ($foreigner->host)
                            {{ $foreigner->host->name }}
                        @endif
                    </td>
                    <td class="main-compare" data-history-main="phone">
                        @if (!empty($foreigner->phone))
                            <nobr>{{ $foreigner->phone }}</nobr>
                        @else
                            &mdash;
                        @endif
                    </td>
                    <td class="main-compare" data-history-main="gender">{{ $foreigner->gender ? 'Женский' : 'Мужской' }}</td>
                    <td class="main-compare" data-history-main="birthday"><nobr>{{ date('d.m.Y', strtotime($foreigner->birthday)) }}</nobr></td>
                </tr>
            </table>
        </div>
    </div>

    @if ($foreigner->history->count() > 1)
        <div class="row mb10">
            <div class="col-md-12">
                <a href="" class="full-foreigner-history btn btn-default btn-sm" data-count="{{ $foreigner->history->count() }}">Показать историю изменений ({{ $foreigner->history->count() }})</a>
            </div>
        </div>
        <div class="row foreigner-history hide">
            <div class="col-md-12 mt10">
                <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>№</th>
                        <th>Дата</th>
                        <th>Изменил</th>
                        <th>ФИО</th>
                        <th>Гражданство</th>
                        <th>Документ</th>
                        <th>Серия</th>
                        <th>Номер</th>
                        <th>Выдан</th>
                        <th>ИНН</th>
                        <th>ОКТМО</th>
                        <th>Адрес&nbsp;регистрации</th>
                        <th>Регистрация&nbsp;до</th>
                        <th>Телефон</th>
                        <th>Пол</th>
                        <th>Дата&nbsp;рождения</th>
                    </tr>
                    @foreach ($foreigner->history as $history)
                        <tr class="history-tr">
                            <td class="nbr">{{ $foreigner->history->count()+1 - $loop->iteration }}</td>
                            <td class="nbr">{{ date('d.m.Y H:i', strtotime($history->created_at)) }}</td>
                            <td class="nbr">{{ $history->operator_name }}</td>
                            <td class="capitalize nbr history-compare" data-history-compare="name">{{ $history->surname }} {{ $history->name }} {{ $history->middle_name }}</td>
                            <td class="capitalize nbr history-compare" data-history-compare="nationality">{{ $history->nationality }} {{ $history->nationality_line2 }}
                            </td>
                            <td class="capitalize nbr history-compare" data-history-compare="document_name">{{ $history->document_name }}</td>
                            <td class="uppercase nbr history-compare" data-history-compare="document_series">
                                @if (!empty($history->document_series))
                                    {{ $history->document_series }}
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="nbr history-compare" data-history-compare="document_number">{{ $history->document_number }}</td>
                            <td class="nbr history-compare" data-history-compare="document_issuedby">{{ date('d.m.Y', strtotime($history->document_date)) }} {{ $history->document_issuedby }}</td>
                            <td class="nbr history-compare" data-history-compare="inn">{{ $history->inn }}</td>
                            <td class="nbr history-compare" data-history-compare="oktmo">{{ $history->oktmo }}</td>
                            <td class="capitalize nbr history-compare" data-history-compare="address">{{ $history->address }} {{ $history->address_line2 }} {{ $history->address_line3 }}</td>
                            <td class="history-compare nbr" data-history-compare="registration_date">{{ date('d.m.Y', strtotime($history->registration_date)) }}</td>
                            <td class="history-compare nbr" data-history-compare="phone">
                                @if (!empty($history->phone))
                                    {{ $history->phone }}
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="nbr history-compare" data-history-compare="gender">{{ $history->gender ? 'Женский' : 'Мужской' }}</td>
                            <td class="nbr history-compare" data-history-compare="birthday">{{ date('d.m.Y', strtotime($history->birthday)) }}</td>
                        </tr>
                    @endforeach
                </table>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left">История обращений</h3>
            @if(Auth::user()->hasRole('admin|administrator|managertm|managerbg|managertmsn'))
                <div class="pull-right mt10">
                    <a class="btn btn-default add-service">Добавить услуги</a>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                @if ($foreigner->servicesByCreated->count() > 0)
                    <table class="table table-bordered table-10px-mb">
                        <tr>
                            <th>№</th>
                            <th>Услуга</th>
                            <th>Цена,&nbsp;руб.</th>
                            <th class="nbr">Дата&nbsp;оказания</th>
                            <th>Менеджер</th>
                            <th>Способ&nbsp;оплаты</th>
                            <th>Статус&nbsp;оплаты</th>
                            <th class="nbr"><abbr title="Пользователь подтвердивший оплату">Подтвердил</abbr></th>
                            @if(Auth::user()->hasRole('admin|cashier|administrator|accountant|chief.accountant'))
                                <th class="nbr">Оплата</th>
                            @endif
                            @if(Auth()->user()->hasRole('admin|administrator|managertm|managertmsn|managermu|managermusn|managerbg|cashier|accountant|chief.accountant|business.manager|business.managerbg'))
                                <th>Возврат</th>
                            @endif
                            <th class="nbr" colspan="2">Печать</th>
                            @if(Auth::user()->hasRole('admin|administrator|managertm|managertmsn|managerbg|business.manager|business.managerbg'))
                                <th class="nbr">Удалить</th>
                            @endif
                        </tr>
                        @foreach ($foreigner->servicesByCreated as $service)
                            <tr @if ($service->payment_status == 2) class="service-archive text-muted hide" @endif>
                                <td>{{ $service->id }}</td>
                                <td>
                                    {{ $service->service_name }}
                                </td>
                                <td>{{ number_format($service->service_price, 0, ',', ' ') }}</td>
                                <td class="nbr">{{ date('d.m.Y H:i', strtotime($service->created_at)) }}</td>
                                <td class="nbr">
                                    @if ($service->operator)
                                        {{ $service->operator->name }}
                                    @endif
                                </td>
                                <td>
                                    {{ $service->payment_method == 0 ? 'Наличными в кассу' : 'Безналичная оплата' }}<br>
                                    @if ($service->client)
                                        {{ $service->client->name }}
                                        <span class="text-muted">ИНН {{ $service->client->inn }}</span>
                                    @endif
                                </td>

                                <!-- статус оплаты -->

                                <td class="nbr">
                                    @if(Auth()->user()->hasRole('admin|administrator|managertm|managertmsn|managerbg|managermu|managermusn|cashier|accountant|chief.accountant|business.manager|business.managerbg'))
                                        @if ($service->repayment_status == 3)
                                            <span class="text-danger">Возврат</span>
                                        @else
                                            @if ($service->payment_status == 0)
                                                &mdash;
                                            @elseif ($service->payment_status == 2)
                                                <span class="text-muted nbr">Архивирована</span>
                                            @else
                                                <span class="text-success nbr">{{ date('d.m.Y H:i', strtotime($service->payment_at)) }}</span>
                                            @endif
                                        @endif
                                    @endif
                                </td>

                                <!-- кассир бухгалтер -->

                                @if(Auth()->user()->hasRole('admin|administrator|managertm|managertmsn|managerbg|managermu|managermusn|cashier|accountant|chief.accountant|business.manager|business.managerbg'))
                                  <td class="nbr">
                                    @if (isset($service->cashier_id))
                                        {{ \MMC\Models\User::find($service->cashier_id)->name }}
                                    @else
                                        &mdash;
                                    @endif
                                  </td>
                                @endif

                                <!-- оплата -->

        						@if(Auth::user()->hasRole('admin|cashier|administrator|accountant|chief.accountant'))
                					<td>
                                        @if ($service->repayment_status != 3 && $service->is_mu != 1)
                                            @if ($service->payment_method)
                                                @if (Auth::user()->hasRole('admin|administrator|accountant|chief.accountant'))
                            						@if ($service->payment_status == 0)
                        								<a href="/operator/foreigners/{{ $service->id }}/servicepay" class="btn btn-success btn-sm btn-block">Подтвердить</a>
                            						@elseif ($service->payment_status == 1)
                                                        <button class="btn btn-default btn-sm btn-block" disabled="disabled">Оплачено</button>
                                                    @elseif ($service->payment_status == 2)
                                                        &mdash;
                                                    @endif
                                                @else
                                                    @if ($service->payment_status == 0 || $service->payment_status == 1)
                                                        <button class="btn btn-default btn-sm btn-block" disabled="disabled">Подтвердить</button>
                                                    @endif
                                                @endif
                                            @else
                                                @if ($service->payment_status == 0)
                                                    <a href="/operator/foreigners/{{ $service->id }}/servicepay" class="btn btn-success btn-sm btn-block">Подтвердить</a>
                                                @elseif ($service->payment_status == 1)
                                                    <button class="btn btn-default btn-sm btn-block" disabled="disabled">Оплачено</button>
                                                @elseif ($service->payment_status == 2)
                                                    <a href="/operator/foreigners/{{ $service->id }}/servicepay" class="btn btn-default btn-sm btn-block">Активировать</a>
                                                @endif
                                            @endif
                						@else
                                            <button class="btn btn-default btn-sm btn-block" disabled="disabled">Подтвердить</button>
                                        @endif
                					</td>
        						@endif

                                <!-- возврат средств -->

        						<td>
                                    @if ($service->is_mu != 1)
                                        @if ($service->repayment_status == 0 && $service->payment_status == 1 && !Auth::user()->hasRole('accountant|chief.accountant|business.manager|business.managerbg'))
                                            <a href="/operator/foreigners/{{ $service->id }}/repayment" class="btn btn-default btn-sm btn-block">Оформить</a>
                                        @elseif ($service->repayment_status == 1 && !Auth::user()->hasRole('administrator|cashier|accountant|chief.accountant|business.manager|business.managerbg'))
                                            <a href="/operator/foreigners/{{ $service->id }}/repayment" class="btn btn-danger btn-sm btn-block">Отменить</a>
                                        @elseif ($service->repayment_status == 1 && Auth::user()->hasRole('administrator|cashier|accountant|chief.accountant'))
                                            <a href="/operator/foreigners/{{ $service->id }}/repayment?status=3" class="btn btn-danger btn-sm btn-block">Подтвердить</a>
                                            <a href="/operator/foreigners/{{ $service->id }}/repayment" class="btn btn-default btn-sm btn-block">Отменить</a>
                                        @elseif ($service->repayment_status == 3)
                                            <span class="text-danger nbr">{{ date('d.m.Y H:i', strtotime($service->updated_at)) }}</span>
                                        @else
                                            @if (Auth::user()->hasRole('administrator|cashier|accountant|chief.accountant'))
                                                <button class="btn btn-default btn-sm btn-block" disabled="disabled">Оформить</a>
                                            @else
                                                <button class="btn btn-default btn-sm btn-block" disabled="disabled">Оформить</a>
                                            @endif
                                        @endif
                                    @else
                                        <button class="btn btn-default btn-sm btn-block" disabled="disabled">Оформить</a>
                                    @endif
                                </td>

                                <td>
                                    @if ($service->is_mu != 1)
                                        <a class="btn btn-default btn-sm btn-block" href="/operator/foreigners/{{ $service->id }}/print">Заявка на услугу</a>
                                    @else
                                        <button class="btn btn-default btn-sm btn-block" disabled="disabled">Заявка на услугу</a>
                                    @endif
                                </td>

                                <td>
                                    @if ($service->is_mu != 1)
                                        @if ($service->repayment_status == 0)
                                            <button class="btn btn-default btn-sm btn-block" disabled="disabled">Заявление на возврат</a>
                                        @else
                                            <a class="btn btn-default btn-sm btn-block" href="/operator/foreigners/{{ $service->id }}/repayment_print">Заявление на возврат</a>
                                        @endif
                                    @else
                                        <button class="btn btn-default btn-sm btn-block" disabled="disabled">Заявление на возврат</a>
                                    @endif
                                </td>

                                @if(Auth::user()->hasRole('admin|administrator|managertm|managertmsn|managerbg|business.manager|business.managerbg'))
                                    <td>
                                        @if ($service->payment_status == 0 && date('Y.m.d', strtotime($service->created_at)) == date('Y.m.d') && $service->is_mu != 1)
                                            <a href="/operator/foreigners/{{ $service->id }}/servicedelete" class="btn btn-danger btn-sm btn-block">Удалить</a>
                                        @else
                                            <button class="btn btn-default btn-sm btn-block" disabled="disabled">Удалить</button>
                                        @endif
                                    </td>
                                @endif

                            </tr>
                        @endforeach
                    </table>
                @else
                    <div class="alert alert-info text-center" role="alert">Услуг нет</div>
                @endif
            </div>
            @if ($foreigner->servicesByCreated->where('payment_status', 2)->count() > 0)
                <a href="" class="full-history btn btn-default btn-sm" data-count="{{ $foreigner->servicesByCreated->count() }}">Показать все обращения ({{ $foreigner->servicesByCreated->count() }})</a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left">Налоги и госпошлины</h3>
            @if(Auth::user()->hasRole('admin|administrator|managertm|managertmsn|managermu|managermusn'))
                <div class="btn-group pull-right mt10">
                    <button class="btn btn-default dropdown-toggle dropdown-menu-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Распечатать QR код&nbsp;<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                         @foreach (\MMC\Models\Tax::all() as $tax)
                            <li><a href="/operator/foreigners/{{ $foreigner->id }}/qrsave/{{ $tax->id }}">{{ $tax->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(Auth::user()->hasRole('managerbg'))
                <div class="btn-group pull-right mt10">
                    <button class="btn btn-default dropdown-toggle dropdown-menu-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Распечатать QR код&nbsp;<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                         @foreach (\MMC\Models\Tax::all() as $tax)
                            @if ($tax->id !== 1)
                                <li><a href="/operator/foreigners/{{ $foreigner->id }}/qrsave/{{ $tax->id }}">{{ $tax->name }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @if ($foreigner->qr->count() > 0)
                <table class="table table-bordered">
                    <tr>
                        <th>№</th>
                        <th>№ чека</th>
                        <th>Налог / госпошлина</th>
                        <th>К&nbsp;оплате,&nbsp;руб.</th>
                        <th>Оплачено,&nbsp;руб.</th>
                        <th>Создан</th>
                        <th>Статус</th>
                        <th>Обновлен</th>
                        <th>Возврат</th>
                    </tr>

                    @foreach ($foreigner->qr as $qr)
                        <tr>
                            <td>
                                @if ($qr->transaction)
                                    {{ $qr->transaction }}
                                @else
                                    Без&nbsp;номера
                                @endif
                            </td>
                            <td>
                                @if ($qr->receipt_id)
                                    {{ $qr->receipt_id }}
                                @else
                                    Без&nbsp;номера
                                @endif
                            </td>
                            <td>
                                @if ($qr->tax)
                                    {{ $qr->tax->name }}
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td>
                                @if (isset($qr->sum) && !empty($qr->sum))
                                    {{ number_format((float) $qr->sum, 2, ',', ' ') }}
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td>
                                @if (isset($qr->sum_from) && !empty($qr->sum_from))
                                    {{ number_format((float) $qr->sum_from, 2, ',', ' ') }}
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="nbr">
                                @if (!$qr->status_datetime)
                                    {{ date('d.m.Y H:i', strtotime($qr->created_at)) }}
                                @else
                                  <abbr title="Занесен в систему: {{ date('d.m.Y H:i', strtotime($qr->status_datetime)) }} (Москва)">{{ date('d.m.Y H:i', strtotime($qr->created_at)) }}</abbr>
                                @endif
                            </td>
                            <td>
                                @if ($qr->status == 2)
                                    <span class="text-danger">{{ $qr->getStatus() }}</span>
                                @else
                                    {{ $qr->getStatus() }}
                                @endif
                            </td>
                            <td class="nbr">
                                {{ date('d.m.Y H:i', strtotime($qr->updated_at)) }}
                            </td>
                            <td>
                                @if ($qr->status == 2)
                                    <a href="/operator/foreigners/{{ $foreigner->id }}/qrreturn/{{ $qr->id }}/print" class="btn btn-default btn-sm btn-block qr-return-print">Распечатать заявление</a>
                                @else
                                    @if ($qr->status == 1 && Auth()->user()->hasRole('administrator|admin'))
                                        <a class="btn btn-default btn-sm qr-return btn-block" data-loading-text="Возврат..." href="/operator/foreigners/{{ $foreigner->id }}/qrreturn/{{ $qr->id }}">Возврат</a>
                                    @else
                                        <button class="btn btn-default btn-sm btn-block" disabled="disabled">Возврат</button>
                                    @endif
                                    <a href="/operator/foreigners/{{ $foreigner->id }}/qrreturn/{{ $qr->id }}/print" class="btn btn-default btn-sm btn-block hide qr-return-print">Распечатать заявление</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div class="alert alert-info text-center" role="alert">Оплаченных налогов нет</div>
            @endif
        </div>
    </div>

    @if(Auth()->user()->hasRole('administrator|admin|managertm|managertmsn|managermu|managermusn|business.manager|accountant|chief.accountant'))
        <div class="row">
            <div class="col-md-12">
                <h3 class="pull-left">Документы</h3>
                @if(Auth()->user()->hasRole('administrator|managertm|managertmsn|managermu|managermusn|business.manager'))
                    <div class="btn-group pull-right mt10">
                        <button class="btn btn-default dropdown-toggle dropdown-menu-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Создать документ&nbsp;<span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="/operator/foreigners/{{ $foreigner->id }}/patent/create">Заявление об оформлении патента</a></li>
                            <li><a href="/operator/foreigners/{{ $foreigner->id }}/patentrecertifying/create">Заявление о переоформлении патента</a></li>
                            <li><a href="/operator/foreigners/{{ $foreigner->id }}/patentchange/create" @if ($foreigner->patent->count() == 0) class="disabled" title="Действие невозможно пока у пользователя нет патента" @endif>Заявление о внесении изменений в патент</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/operator/foreigners/{{ $foreigner->id }}/ig/create">Заявление о прибытии ИГ</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/operator/foreigners/{{ $foreigner->id }}/dms/create">Заявление о выдаче полиса ДМС</a></li>
                            @if (Auth::user()->is_have_access_strict_report)
                                <li role="separator" class="divider"></li>
                                <li><a href="/operator/foreigners/{{ $foreigner->id }}/blank/create">Бланк строгой отчетности</a></li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if ($foreigner->documentIsset())
                    <table class="table table-bordered m-align">
                        <tr>
                            <th style="border-left: 3px solid #ebebeb;">Документ</th>
                            <th>Автор</th>
                            <th>Создан</th>
                            <th>Обновил</th>
                            <th>Обновлен</th>
                            <th colspan="4"></th>
                        </tr>
                        @foreach ($foreigner->patent as $patent)
                            <tr>
                                <td
                                        class="
                                            @if ($patent->doc_status == 0)
                                                registry-status-0
                                            @elseif ($patent->doc_status == 1)
                                                registry-status-1
                                            @else
                                                registry-status-2
                                            @endif
                                        "
                                    >Заявление об оформлении патента</td>
                                <td>
                                    @if (isset($patent->operator))
                                        {{ $patent->operator->name }}
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td class="nbr">
                                    {{ date('d.m.Y H:i', strtotime($patent->created_at)) }}
                                </td>
                                <td>
                                    @if (isset($patent->updatedby))
                                        {{ $patent->updatedby->name }}
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td class="nbr">
                                    {{ date('d.m.Y H:i', strtotime($patent->updated_at)) }}
                                </td>
                                <td width="120px"><a class="btn btn-default btn-sm btn-block" href="/operator/foreigners/{{ $foreigner->id }}/patent/{{ $patent->id }}/edit" @if (!Helper::canBeEdit($patent->created_at)) disabled @endif>Заполнить и распечатать</a></td>
                                <td width="110px"><a class="btn btn-default btn-sm btn-block" href="/operator/foreigners/{{ $foreigner->id }}/patent/{{ $patent->id }}">Распечатать</a></td>
                                <td width="110px">
                                  @if ($patent->doc_status != 0)
                                    <span class="btn btn-default btn-sm btn-block disabled">В реестре</span>
                                  @else
                                    @if (!Auth::user()->is_have_access_registry)
                                      <span class="btn btn-default btn-sm btn-block disabled">Передать в реестр</span>
                                    @else
                                      <a class="btn btn-default btn-sm btn-block send-to-registry" href="/operator/foreigners/{{ $foreigner->id }}/patent/{{ $patent->id }}/registry">Передать в реестр</a>
                                    @endif
                                  @endif
                                </td>
                                @if(Auth()->user()->hasRole('administrator|business.manager'))
                                    <td width="80px"><a class="btn btn-danger ajax-delete btn-sm btn-block" href="/operator/foreigners/{{ $foreigner->id }}/patent/{{ $patent->id }}">Удалить</a></td>
                                @endif
                            </tr>
                        @endforeach
                        @foreach ($foreigner->patentrecertifying as $patentrecertifying)
                            <tr>
                                <td
                                        class="
                                            @if ($patentrecertifying->doc_status == 0)
                                                registry-status-0
                                            @elseif ($patentrecertifying->doc_status == 1)
                                                registry-status-1
                                            @else
                                                registry-status-2
                                            @endif
                                        "
                                    >Заявление о переоформление патента</td>
                                <td>
                                    @if (isset($patentrecertifying->operator))
                                        {{ $patentrecertifying->operator->name }}
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td class="nbr">
                                    {{ date('d.m.Y H:i', strtotime($patentrecertifying->created_at)) }}
                                </td>
                                <td>
                                    @if (isset($patentrecertifying->updatedby))
                                        {{ $patentrecertifying->updatedby->name }}
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td class="nbr">
                                    {{ date('d.m.Y H:i', strtotime($patentrecertifying->updated_at)) }}
                                </td>
                                <td width="120px"><a class="btn btn-default btn-sm btn-block" href="/operator/foreigners/{{ $foreigner->id }}/patentrecertifying/{{ $patentrecertifying->id }}/edit" @if (!Helper::canBeEdit($patentrecertifying->created_at)) disabled @endif>Заполнить и распечатать</a></td>
                                <td width="110px"><a class="btn btn-default btn-sm btn-block" href="/operator/foreigners/{{ $foreigner->id }}/patentrecertifying/{{ $patentrecertifying->id }}">Распечатать</a></td>
                                <td width="110px">
                                  @if ($patentrecertifying->doc_status != 0)
                                    <span class="btn btn-default btn-sm btn-block disabled">В реестре</span></td>
                                  @else
                                    @if (!Auth::user()->is_have_access_registry)
                                      <span class="btn btn-default btn-sm btn-block disabled">Передать в реестр</span></td>
                                    @else
                                      <a class="btn btn-default btn-sm btn-block send-to-registry" href="/operator/foreigners/{{ $foreigner->id }}/patentrecertifying/{{ $patentrecertifying->id }}/registry">Передать в реестр</span></td>
                                    @endif
                                  @endif
                                @if(Auth()->user()->hasRole('administrator|business.manager'))
                                    <td width="80px"><a class="btn btn-danger ajax-delete btn-sm pull-right" href="/operator/foreigners/{{ $foreigner->id }}/patentrecertifying/{{ $patentrecertifying->id }}">Удалить</a></td>
                                @endif
                            </tr>
                        @endforeach
                        @foreach ($foreigner->patentchange as $patentchange)
                            <tr>
                                <td style="border-left: 3px solid">Заявление о внесении изменений в патент</td>
                                <td>
                                    @if (isset($patentchange->operator))
                                        {{ $patentchange->operator->name }}
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td class="nbr">
                                    {{ date('d.m.Y H:i', strtotime($patentchange->created_at)) }}
                                </td>
                                <td>
                                    @if (isset($patentchange->updatedby))
                                        {{ $patentchange->updatedby->name }}
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td class="nbr">
                                    {{ date('d.m.Y H:i', strtotime($patentchange->updated_at)) }}
                                </td>
                                <td width="120px"><a class="btn btn-default btn-sm pull-right" href="/operator/foreigners/{{ $foreigner->id }}/patentchange/{{ $patentchange->id }}/edit" @if (!Helper::canBeEdit($patentchange->created_at)) disabled @endif>Заполнить и распечатать</a></td>
                                <td width="110px"><a class="btn btn-default btn-sm pull-right" href="/operator/foreigners/{{ $foreigner->id }}/patentchange/{{ $patentchange->id }}">Распечатать</a></td>
                                <td width="110px">&mdash;</td>
                                @if(Auth()->user()->hasRole('administrator|business.manager'))
                                    <td width="80px"><a class="btn btn-danger ajax-delete btn-sm pull-right" href="/operator/foreigners/{{ $foreigner->id }}/patentchange/{{ $patentchange->id }}">Удалить</a></td>
                                @endif
                            </tr>
                        @endforeach
                        @foreach ($foreigner->ig as $ig)
                            <tr>
                                <td style="border-left: 3px solid">Заявление о прибытии ИГ</td>
                                <td>
                                    @if (isset($ig->operator))
                                        {{ $ig->operator->name }}
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td class="nbr">
                                    {{ date('d.m.Y H:i', strtotime($ig->created_at)) }}
                                </td>
                                <td>
                                    @if (isset($ig->updatedby))
                                        {{ $ig->updatedby->name }}
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td class="nbr">
                                    {{ date('d.m.Y H:i', strtotime($ig->updated_at)) }}
                                </td>
                                <td width="120px"><a class="btn btn-default btn-sm pull-right" href="/operator/foreigners/{{ $foreigner->id }}/ig/{{ $ig->id }}/edit" @if (!Helper::canBeEdit($ig->created_at)) disabled @endif>Заполнить и распечатать</a></td>
                                <td width="110px"><a class="btn btn-default btn-sm pull-right" href="/operator/foreigners/{{ $foreigner->id }}/ig/{{ $ig->id }}">Распечатать</a></td>
                                <td width="110px">&mdash;</td>
                                @if(Auth()->user()->hasRole('administrator|business.manager'))
                                    <td width="80px"><a class="btn btn-danger ajax-delete btn-sm pull-right" href="/operator/foreigners/{{ $foreigner->id }}/ig/{{ $ig->id }}">Удалить</a></td>
                                @endif
                            </tr>
                        @endforeach
                        @foreach ($foreigner->dms as $dms)
                            <tr>
                                <td style="border-left: 3px solid">Полис ДМС</td>
                                <td>
                                    @if (isset($dms->operator))
                                        {{ $dms->operator->name }}
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td class="nbr">
                                    {{ date('d.m.Y H:i', strtotime($dms->created_at)) }}
                                </td>
                                <td>
                                    @if (isset($dms->updatedby))
                                        {{ $dms->updatedby->name }}
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td class="nbr">
                                    {{ date('d.m.Y H:i', strtotime($dms->updated_at)) }}
                                </td>
                                <td width="120px"><a class="btn btn-default btn-sm pull-right" href="/operator/foreigners/{{ $foreigner->id }}/dms/{{ $dms->id }}/edit" @if (!Helper::canBeEdit($dms->created_at)) disabled @endif>Заполнить и распечатать</a></td>
                                <td width="110px"><a class="btn btn-default btn-sm pull-right" href="/operator/foreigners/{{ $foreigner->id }}/dms/{{ $dms->id }}">Распечатать</a></td>
                                <td width="110px">&mdash;</td>
                                @if(Auth()->user()->hasRole('administrator|business.manager'))
                                    <td width="80px"><a class="btn btn-danger ajax-delete btn-sm pull-right" href="/operator/foreigners/{{ $foreigner->id }}/dms/{{ $dms->id }}">Удалить</a></td>
                                @endif
                            </tr>
                        @endforeach
                        @foreach ($foreigner->blanks as $blank)
                            <tr>
                                <td style="border-left: 3px solid #9b59b6">Бланк строгой отчетности ({{ $blank->full_number }})</td>
                                <td>
                                    @if (isset($blank->operator))
                                        {{ $blank->operator->name }}
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td class="nbr">
                                    {{ date('d.m.Y H:i', strtotime($blank->created_at)) }}
                                </td>
                                <td>
                                    &mdash;
                                </td>
                                <td class="nbr">
                                    &mdash;
                                </td>
                                <td width="120px">&mdash;</td>
                                <td width="110px"><a class="btn btn-default btn-sm pull-right" href="/operator/foreigners/{{ $foreigner->id }}/blank/{{ $blank->id }}">Распечатать</a></td>
                                <td width="110px">&mdash;</td>
                                @if(Auth()->user()->hasRole('administrator'))
                                    <td width="80px"><a class="btn btn-danger ajax-delete btn-sm pull-right" href="/operator/foreigners/{{ $foreigner->id }}/blank/{{ $blank->id }}">Удалить</a></td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                @else
                    <div class="alert alert-info text-center" role="alert">Документы не оформлялись</div>
                @endif
            </div>
        </div>
    @endif

    @include('modals.new-service-modal')

@endsection
