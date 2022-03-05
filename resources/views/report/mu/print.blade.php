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

    <div class="without-padding mt20">
        <a href="/operator/report/mu?daterange={{ Request::get('daterange') }}&client={{ Request::get('client') }}&manager={{ Request::get('manager') }}" class="btn btn-danger pull-right">Закрыть</a>
    </div>

    <div class="row mt20">
        <div class="col-lg-12">
            <p class="text-muted"><small>Подготовлено в ММЦ Онлайн {{ date('d.m.Y в H:i') }}</small></p>
            @if(Auth()->user()->hasRole('managermu|managermusn'))
                <h3 class="pull-left">Отчет о заявках</h3>
            @else
                <h3 class="pull-left">Отчет о заявках на услуги МУ</h3>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered mt20">
                        <tr>
                            <th style="width:100%">Менеджер</th>
                            <th class="tw100">Период&nbsp;отчета</th>
                            <th class="tw100">Плательщиков</th>
                            <th class="tw100">Документов</th>
                            <th class="tw100">Услуг&nbsp;(ФЛ)</th>
                            <th class="tw100">Услуг&nbsp;(ЮЛ)</th>
                            <th class="tw100">Итого&nbsp;(ФЛ),&nbsp;руб.</th>
                            <th class="tw100">Итого&nbsp;(ЮЛ),&nbsp;руб.</th>
                        </tr>
                        <tr>
                            <td>
                                @if (Request::has('manager'))
                                    {{ \MMC\Models\User::find(Request::get('manager'))->name }}
                                @else
                                    @if (Auth::user()->hasRole('managermu'))
                                        {{ Auth::user()->name }}
                                    @else
                                        Все менеджеры
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
                            <td>{{ $reportServices->unique('client_id')->count() }}</td>
                            <td>{{ $totalDocuments }}</td>
                            <td>{{ $totalIndividualServices }}</td>
                            <td>{{ $totalLegalServices }}</td>
                            <td><nobr>{{ number_format($totalIndividualPrice, 0, ',', ' ') }}</nobr></td>
                            <td><nobr>{{ number_format($totalLegalPrice, 0, ',', ' ') }}</nobr></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php $count = 1 ?>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <tr>
                    <th class="tc35 text-center">№</th>
                    <th>Плательщик</th>
                    <th>Услуга</th>
                    <th>Количество</th>
                    <th>Сумма,&nbsp;руб.</th>
                    <th>Способ&nbsp;оплаты</th>
                    <th>Статус&nbsp;оплаты</th>
                    <th>Кассир&nbsp;/&nbsp;Бухгалтер</th>
                </tr>

                @foreach ($reportServices as $service)
                    <tr>
                        <td class="text-center">{{ $count++ }}</td>
                        <td>{{ $service->client->name }}</td>
                        <td>{{ $service->service->name }}</td>
                        <td class="text-right nbr">{{ $service->service_count }}</td>
                        <td class="text-right nbr">{{ number_format($service->service_price*$service->service_count, 0, ',', ' ') }}</td>
                        <td>
                    		@if ($service->payment_method)
                				Безналичная оплата
                    		@else
                				Наличными в кассу
                    		@endif
                        </td>
                        <td>
                            {{-- \MMC\Models\ForeignerService::$statuses[$service->payment_status] --}}
                            @if ($service->payment_status)
                                {{ date('d.m.Y H:i', strtotime($service->payment_at)) }}
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td>
                        	@if (isset($service->cashier_id))
                        		{{ \MMC\Models\User::find($service->cashier_id)->name }}
                        	@endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <script>
        window.print();
    </script>
@endsection
