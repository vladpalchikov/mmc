@extends('layouts.master')

@section('title', 'Касса')

@section('menu')
    @if(Auth()->user()->hasRole('admin'))
        @include('partials.admin-menu', ['active' => 'cash'])
    @else
        @include('partials.operator-menu', ['active' => 'cash'])
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Оплата через кассу</h3>
        </div>
    </div>

    <div class="row well mt20">
        <div class="col-md-6">
            <form action="/operator/cash" method="GET" class="form-inline pull-left" style="width:100%">
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
                <a href="/operator/cash?client={{ Request::get('client') }}&search={{ Request::get('search') }}" class="btn btn-default @if (!Request::has('module')) btn-primary @endif">ТМ ({{ \MMC\Models\ForeignerService::countCash() }})</a>
                <a href="/operator/cash?module=1&client={{ Request::get('client') }}&search={{ Request::get('search') }}" class="btn btn-default @if (Request::has('module')) btn-primary @endif">МУ ({{ \MMC\Models\ForeignerServiceGroup::countCash() }})</a>
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
                                <th>Количество</th>
                                <th>Сумма,&nbsp;руб.</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <td style="width: 40px;">{{ $service->id }}</td>
                                    <td style="width: 20%;">
                                        <span class="nbr tx-owr">{{ $service->service_name }}</span>
                                    </td>
                                    <td class="nbr" style="width: 25%;">
                                        @if ($service->client_id)
                                            <a href="/operator/clients/{{ $service->client_id }}">{{ strtoupper($service->client->name) }}</a>
                                        @else
                                            @if ($service->foreigner)
                                                {{ strtoupper($service->foreigner->document_series) }}{{ $service->foreigner->document_number }} - 
                                                <a href="/operator/foreigners/{{ $service->foreigner->id }}">{{ title_case($service->foreigner->surname.' '.$service->foreigner->name.' '.$service->foreigner->middle_name) }}</a>
                                            @else
                                                &mdash;
                                            @endif
                                        @endif
                                    </td>
                                    <td class="nbr" style="width: 20%;">{{ date('d.m.Y H:i', strtotime($service->created_at)) }}</td>
                                    <td class="nbr" style="width: 20%;">{{ $service->operator->name }}</td>
                                    <td class="nbr" style="min-width: 150px; text-align:right">{{ isset($service->service_count) ? $service->service_count : 1 }}</td>
                                    <td style="min-width: 150px;text-align:right">
                                        @if (isset($service->service_count))
                                            {{ number_format($service->service_count * $service->service_price, 0, ',', ' ') }}
                                        @else
                                            {{ number_format($service->service_price, 0, ',', ' ') }}
                                        @endif
                                    </td>
                                    <td style="width: 100px;">
                                        <a href="" class="btn btn-default btn-block btn-sm cash-pay" data-id="{{ $service->id }}" data-type="{{ $service instanceof \MMC\Models\ForeignerService ? 'tm' : 'mu' }}">Подтвердить оплату</a>
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
