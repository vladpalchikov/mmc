@extends('layouts.master')

@section('title', 'Б/Н')

@section('menu')
    @if(Auth()->user()->hasRole('admin'))
        @include('partials.admin-menu', ['active' => 'cashless'])
    @else
        @include('partials.operator-menu', ['active' => 'cashless'])
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Безналичная оплата</h3>
        </div>
    </div>

    <div class="row well mt20">
        <div class="col-md-6">
            <form action="/operator/cashless" method="GET" class="form-inline pull-left" style="width:100%">
                <input type="hidden" name="module" value="{{ Request::get('module') }}">
                <input type="text" name="search" autofocus placeholder="Укажите № заявки, нажмите Enter" class="form-control" value="{{ Request::get('search') }}" style="width: 100%">
                <input type="submit" class="btn btn-default float-search-button" value="Найти">
            </form>
        </div>

        <div class="col-md-6">
            <form class="form-inline pull-right">
                <input type="hidden" name="module" value="{{ Request::get('module') }}">
                <select name="client" class="form-control">
                    <option value="">Все плательщики</option>
                    @foreach($clients as $client)
                        <option @if(Request::get('client') == $client->id) selected @endif value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
                <input type="submit" class="btn btn-primary" value="Выбрать">
            </form>

            <div class="btn-group pull-right mr10" role="group" aria-label="...">
                @if(Auth::user()->hasRole('administrator|chief.accountant|accountant'))
                    <a href="/operator/cashless?client={{ Request::get('client') }}&search={{ Request::get('search') }}" class="btn btn-default @if (!Request::has('module')) btn-primary @endif">ТМ ({{ \MMC\Models\ForeignerService::countCashless() }})</a>
                    <a href="/operator/cashless?module=1&client={{ Request::get('client') }}&search={{ Request::get('search') }}" class="btn btn-default @if (Request::has('module')) btn-primary @endif">МУ ({{ \MMC\Models\ForeignerServiceGroup::countCashless() }})</a>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                @if ($services->count() > 0)
                    <table class="table table-bordered table-hover m-align">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Услуга</th>
                                <th>Плательщик</th>
                                <th>Дата&nbsp;оформления</th>
                                <th>Менеджер</th>
                                <th>Оператор</th>
                                <th>Количество</th>
                                <th class="nbr">Сумма,&nbsp;руб.</th>
                                <th class="nbr">Баланс, руб.</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                @php
                                    $companyBalance = $service->service->company->getBalance($service->client);
                                @endphp
                                <tr>
                                    <td style="width: 40px;">{{ $service->id }}</td>
                                    <td style="width: 20%;">
                                        <span class="nbr tx-owr">{{ $service->service_name }}</span>
                                    </td>
                                    <td class="nbr" style="width: 25%;">
                                        @if ($service->client_id)
                                            <a href="/operator/clients/{{ $service->client_id }}">{{ $service->client->name }}</a>
                                        @else
                                            &mdash;
                                        @endif
                                    </td>
                                    <td class="nbr" style="width: 20%;">{{ date('d.m.Y H:i', strtotime($service->created_at)) }}</td>
                                    <td class="nbr" style="width: 20%;">{{ $service->operator->name }}</td>
                                    <td class="nbr" style="width: 20%;">{{ $service->service->company->name }}</td>
                                    <td class="nbr" style="min-width: 150px; text-align: right">{{ isset($service->service_count) ? $service->service_count : 1 }}</td>
                                    <td style="min-width: 150px; text-align: right">
                                        @if (isset($service->service_count))
                                            {{ number_format($service->service_count * $service->service_price, 0, ',', ' ') }}
                                        @else
                                            {{ number_format($service->service_price, 0, ',', ' ') }}
                                        @endif
                                    </td>
                                    <td class="nbr company-balance" style="width: 20%; text-align: right" data-company-id="{{ $service->service->company->id }}" data-client-id="{{ $service->client_id }}"><span  @if ($companyBalance < 0) class="text-danger" @endif>{{ number_format($companyBalance, 0, ',', ' ') }}</span></td>
                                    <td style="width: 100px;">
                                        @if ($service->service_price > $companyBalance)
                                            <button class="btn btn-default btn-block btn-sm" disabled>Подтвердить оплату</a>
                                        @else
                                            <a href="" class="btn btn-default btn-block btn-sm cashless-pay" data-id="{{ $service->id }}" data-type="{{ $service instanceof \MMC\Models\ForeignerService ? 'tm' : 'mu' }}">Подтвердить оплату</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    @if (app('request')->input('search'))
                        <div style="padding-top: 5%; padding-bottom: 5%;">
                            <div class="text-center">
                                <h3>Такой заявки нет</h3>
                                <p>Попробуйте поискать по другим параметрам</p>
                            </div>
                        </div>
                    @else
                        <div style="padding-top: 5%; padding-bottom: 5%;">
                            <div class="text-center">
                                <h3 style="font-size:40px;">Заявок нет</h3>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
            <div class="pull-right">
                {{ $services->links() }}
            </div>
        </div>
    </div>
@endsection
