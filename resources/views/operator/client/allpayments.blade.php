@extends('layouts.master')

@section('title', $client->name)

@section('menu')
    @include('partials.operator-menu', ['active' => 'clients'])
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/operator/clients/{{ $client->id }}">{{ $client->name }}</a></li>
                <li class="active">История обращений</li>
            </ol>
        </div>
    </div>

    <div class="row mt10">
        <div class="col-md-12">
            <table class="table table-bordered table-hover m-align">
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
                    <th>Кассир&nbsp;/&nbsp;Бухгалтер</th>
                    @if(Auth::user()->hasRole('administrator'))
                        <th>Оплата</th>
                    @endif
                    <th>Заявка</th>
                    <th>Удаление</th>
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
                                @if (!$service->is_mu)
                                    <a href="/operator/foreigners/{{ $service->foreigner->id }}">{{ $service->id }}</a>
                                @else
                                    {{ $service->id }}
                                @endif
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
                            @if(Auth::user()->hasRole('administrator'))
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
                        @if(Auth::user()->hasRole('administrator'))
                            <td>
                                @if ($service->payment_status == 0)
                                    @if ($service->service_price > $service->service->company->getBalance($service->client) || isset($service->is_mu))
                                        <button class="btn btn-default btn-block btn-sm" disabled>Подтвердить</a>
                                    @else
                                        <a href="/operator/muservices/{{ $service->id }}/servicepay" class="btn btn-success btn-sm btn-block">Подтвердить</a>
                                    @endif
                                @elseif ($service->payment_status == 1)
                                    &mdash;
                                    {{-- <a href="/operator/muservices/{{ $service->id }}/servicepay" class="btn btn-danger btn-sm btn-block @if (isset($service->is_mu)) disabled @endif">Отменить</a> --}}
                                @elseif ($service->payment_status == 2)
                                    <a href="/operator/muservices/{{ $service->id }}/servicepay" class="btn btn-default btn-sm btn-block @if (isset($service->is_mu)) disabled @endif">Активировать</a>
                                @endif
                            </td>
                        @endif
                        <td>
                            <a href="/operator/muservices/{{ $service->id }}/print" class="btn btn-default btn-sm btn-block @if (isset($service->is_mu)) disabled @endif">Распечатать</a>
                        </td>
                        <td>
                            @if ($service->payment_status == 0 && (\Carbon\Carbon::parse($service->created_at)->diffInHours(\Carbon\Carbon::now()) < 24 ))
                                <a href="/operator/muservices/{{ $service->id }}" class="btn btn-danger btn-sm ajax-delete btn-block @if (isset($service->is_mu)) disabled @endif">
                                    Удалить
                                </a>
                            @else
                                <button class="btn btn-default btn-sm btn-block" disabled="disabled">Удалить</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="pull-right">
        {{ $services->links() }}
    </div>
@endsection
