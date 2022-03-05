@extends('layouts.print_report')

@section('content')
    <style>
        * {
            font-size: 12px;
        }
        .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border: 1px solid #000 !important;
        }
        @media print {
            .btn-danger {
                display: none;
            }
        }
    </style>

    <div class="row mt20">
        <div class="col-md-12">
            <a href="/operator/report/cashbox?daterange={{ Request::get('daterange') }}&manager={{ Request::get('manager') }}&mmc={{ Request::get('mmc') }}&cashier={{ Request::get('cashier') }}&client={{ Request::get('client') }}" class="btn btn-danger pull-right">Закрыть</a>
        </div>
    </div>

    @if ($totalServices > 0)
        <div class="row">
            <div class="col-lg-12">
                <p class="text-muted"><small>Подготовлено в ММЦ Онлайн {{ date('d.m.Y в H:i') }}</small></p>
                <h3>Отчет об оказанных услугах</h3>
                <table class="table table-bordered mt20">
                    <tr>
                        <th style="width: 100%">Область&nbsp;отчета</th>
                        <th class="tw100">Период&nbsp;отчета</th>
                        <th class="tw100">Кассир</th>
                        <th class="tw100">Оператор</th>
                        <th class="tw100">Плательщик</th>
                        <th class="tw100">Услуг&nbsp;оплачено</th>
                        <th class="tw100">Итого,&nbsp;руб.</th>
                    </tr>
                    <tr>
                        <td>
                            @if (Request::has('mmc'))
                                {{ \MMC\Models\MMC::find(Request::get('mmc'))->name }}
                            @else
                                @if (Auth::user()->hasRole('business.manager|accountant|cashier'))
                                    {{ Auth::user()->mmc->name }}
                                @else
                                   Все&nbsp;юниты
                                @endif
                            @endif
                        </td>
                        <td class="nbr">
                          @if (count($daterange) > 1)
                              @if ($daterange[0] != $daterange[1])
                                  {{ Helper::dateFromString($daterange[0]) }} - {{ Helper::dateFromString($daterange[1]) }}
                              @else
                                  {{ Helper::dateFromString($daterange[0]) }}
                              @endif
                          @else
                              {{ Helper::dateFromString($daterange[0]) }}
                          @endif
                        </td>
                        <td class="nbr">
                            @if (Request::has('cashier'))
                                {{ \MMC\Models\User::find(Request::get('cashier'))->name }}
                            @else
                                @if (Request::has('cashier'))
                                    {{ \MMC\Models\User::find(Request::get('cashier'))->name }}
                                @else
                                    @if (Auth::user()->hasRole('cashier'))
                                        {{ Auth::user()->name }}
                                    @else
                                        Все кассиры
                                    @endif
                                @endif
                            @endif
                        </td>
                        <td class="nbr">
                            @if (Request::has('company'))
                                {{ \MMC\Models\Company::find(Request::get('company'))->name }}
                            @else
                                Все операторы
                            @endif
                        </td>
                        <td class="nbr">
                            @if (Request::has('client'))
                                {{ \MMC\Models\Client::find(Request::get('client'))->name }}
                            @else
                                Все плательщики&nbsp;({{ \MMC\Models\Client::count() }})
                            @endif
                        </td>
                        <td class="nbr">{{ $totalServices }}</td>
                        <td class="nbr">{{ number_format($totalPrice, 0, ',', ' ') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    @endif

    @if ($totalServices > 0)
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <tr>
                    <th>Услуга</th>
                    <th>Оператор</th>
                    @if(Auth()->user()->hasRole('administrator|business.manager|accountant|chief.accountant'))
                        <th>Агентское вознаграждение</th>
                        <th>Сумма принципалу</th>
                    @endif
                    <th class="tw80">Цена</th>
                    <th class="tw80">Количество</th>
                    @if(Auth()->user()->hasRole('administrator|business.manager|accountant|chief.accountant'))
                        <th class="tw80">Итого&nbsp;агент</th>
                        <th class="tw80">Итого&nbsp;принципал</th>
                    @endif
                    <th class="tw80 nbr">Итого, руб.</th>
                </tr>
                @foreach ($reportServices as $name => $service)
                    @if ($service['count'] > 0)
                        <tr>
                            <td class="nbr">{{ $name }}</td>
                            <td class="nbr">{{ $service['company'] }}</td>
                            @if(Auth()->user()->hasRole('administrator|business.manager|accountant|chief.accountant'))
                                <td class="text-right nbr">{{ number_format($service['agent_compensation'], 0, ',', ' ') }}</td>
                                <td class="text-right nbr">{{ number_format($service['principal_sum'], 0, ',', ' ') }}</td>
                            @endif
                            <td class="text-right nbr">{{ number_format($service['price'], 0, ',', ' ') }}</td>
                            <td class="text-right nbr">{{ $service['count'] }}</td>
                            @if(Auth()->user()->hasRole('administrator|business.manager|accountant|chief.accountant'))
                                <td class="text-right nbr"><nobr>{{ number_format($service['total_agent_compensation'], 0, ',', ' ') }}</nobr></td>
                                <td class="text-right nbr"><nobr>{{ number_format($service['total_principal_sum'], 0, ',', ' ') }}</nobr></td>
                            @endif
                            <td class="text-right nbr"><nobr>{{ number_format($service['total_price'], 0, ',', ' ') }}</nobr></td>
                        </tr>
                    @endif
                @endforeach
                    <tr class="active">
                        <td><strong>Всего:</strong></td>
                        @if(Auth()->user()->hasRole('administrator|business.manager|accountant|chief.accountant'))
                            <td></td>
                            <td></td>
                        @endif
                        <td></td>
                        <td></td>
                        <td class="text-right nbr"><strong>{{ $totalServices }}</strong></td>
                        @if(Auth()->user()->hasRole('administrator|business.manager|accountant|chief.accountant'))
                            <td class="text-right nbr"><strong>{{ number_format($totalPriceAgent, 0, ',', ' ') }}</strong></td>
                            <td class="text-right nbr"><strong>{{ number_format($totalPricePrincipal, 0, ',', ' ') }}</strong></td>
                        @endif
                        <td class="text-right nbr"><strong>{{ number_format($totalPrice, 0, ',', ' ') }}</strong></td>
                    </tr>
            </table>
        </div>
    </div>
    @else
        <div class="alert alert-info text-center" role="alert">Нет данных</div>
    @endif

    <script>
        window.print();
    </script>
@endsection
