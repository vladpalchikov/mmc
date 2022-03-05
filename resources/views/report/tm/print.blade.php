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
            <a href="/operator/report/tm" class="btn btn-danger pull-right">Закрыть</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <p class="text-muted"><small>Подготовлено в ММЦ Онлайн {{ date('d.m.Y в H:i') }}</small></p>
            <h3 class="pull-left">Отчет о заявках</h3>
            <table class="table table-bordered mt20">
                <tr>
                	<th style="width:100%">Менеджер</th>
                	<th class="tw100">Период&nbsp;отчета</th>
                	<th class="tw100">Граждан</th>
                    <th class="tw100">Услуг&nbsp;оплачено</th>
                	<th class="tw100">Документов</th>
                	<th class="tw100">Итого,&nbsp;руб.</th>
                </tr>

                <tr>
                    <td>
                        @if (Request::has('manager'))
                            {{ \MMC\Models\User::find(Request::get('manager'))->name }}
                        @else
                            {{ Auth::user()->name }}
                        @endif
                    </td>
                    <td class="nbr">
                        @if (count($daterange) > 1)
                            @if ($daterange[0] != $daterange[1])
                                {{ $daterange[0] }} - {{ $daterange[1] }}
                            @else
                                {{ $daterange[0] }}
                            @endif
                        @else
                            {{ $daterange[0] }}
                        @endif
                    </td>
                    <td>{{ $reportServices->unique('foreigner_id')->count() }}</td>
                    <td>{{ $totalPayment }}</td>
                    <td>{{ $totalDocuments }}</td>
                    <td><nobr>{{ number_format($totalPrice, 0, ',', ' ') }}</nobr></td>
                </tr>
            </table>
        </div>
    </div>

    <?php $count = 1 ?>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered mt20">
                <tr>
                    <th style="width: 1%">№</th>
                    <th>Иностранный&nbsp;гражданин</th>
                    <th>Услуга</th>
                    <th>Сумма</th>
                    <th>Статус&nbsp;оплаты</th>
                    <th>Способ&nbsp;оплаты</th>
                    <th>Кассир&nbsp;/&nbsp;Бухгалтер</th>
                </tr>

                @foreach ($reportServices as $service)
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td class="capitalize">{{ $service->foreigner->surname }} {{ $service->foreigner->name }} {{ $service->foreigner->middle_name }}</td>
                        <td>{{ $service->service->name }}</td>
                        <td class="text-right nbr">{{ number_format($service->service_price, 0, ',', ' ') }}</td>
                        <td>
                            @if ($service->repayment_status == 3)
                                Возврат
                            @else
                                {{-- \MMC\Models\ForeignerService::$statuses[$service->payment_status] --}}
                                @if ($service->payment_status)
                                    {{ date('d.m.Y H:i', strtotime($service->payment_at)) }}
                                @else
                                    &mdash;
                                @endif
                            @endif
                        </td>
                        <td>{{ $service->payment_method == 0 ? 'Наличными в кассу' : 'Безналичная оплата' }}</td>
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
