@extends('layouts.print_report')

@section('content')
    <style>
			* {
				font-size: 12px;
			}
        .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border: 1px solid #000 !important;
            font-size: 12px;
        }
        @media print {
            .btn-danger {
                display: none;
            }
            .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
                border: 1px solid #000 !important;
                font-size: 11px;
            }
        }
    </style>

    <div class="row mt20">
        <div class="col-md-12">
            <a href="/operator/report/summary" class="btn btn-danger pull-right">Закрыть</a>
        </div>
    </div>

    @if ($totalServices > 0)
        <div class="row">
            <div class="col-lg-12">
            	<p class="text-muted"><small>Подготовлено в ММЦ Онлайн {{ date('d.m.Y в H:i') }}</small></p>
            	<h3>Отчет о работе менеджеров</h3>
    			<table class="table table-bordered">
    				<tr>
    					<th style="width: 100%">Область&nbsp;отчета</th>
    					<th class="tw100">Период&nbsp;отчета</th>
    					<th class="tw100">Граждан</th>
                        <th class="tw100">Услуг&nbsp;оплачено</th>
                        <th class="tw100">Итого,&nbsp;руб.</th>
    				</tr>
    				<tr>
    					<td>
                            @if (Request::has('mmc'))
                                {{ \MMC\Models\MMC::find(Request::get('mmc'))->name }}
                            @else
                                @if (Auth::user()->hasRole('business.manager|accountant'))
                                    {{ Auth::user()->mmc->name }}
                                @else
                                    Все&nbsp;ММЦ
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
                        <td class="nbr">{{ $totalForeigners }}</td>
                        <td class="nbr">{{ $totalPayment }}</td>
                        <td class="nbr">{{ number_format($totalPrice, 0, ',', ' ') }}</td>
    				</tr>
    			</table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-hover">
                    <tr>
                        <th style="width: 100%">Менеджер</th>
                        <th class="tw80">Роль</th>
                        <th class="tw80">Граждан</th>
                        <th class="tw80">Услуг</th>
                        <th class="tw80">Возвратов</th>
                        <th class="tw80">Оплачено</th>
                        <th class="tw80">Документов</th>
                        <th class="tw80"><div><span>Итого,&nbsp;руб.</span></div></th>
                    </tr>
                    @foreach ($servicesByOperator as $row)
                        <tr>
                            <td style="overflow-y: hidden;" class="nbr">
                                {{ $row['operator']->name }}
                            </td>
                            <td style="overflow-y: hidden;" class="nbr">
                                {{ $row['operator']->role }}
                            </td>
                            <td class="text-right">
                                @if ($row['totalClients'] == 0)
                                    -
                                @else
                                    {{ $row['totalClients'] }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if ($row['totalServices'] == 0)
                                    -
                                @else
                                    {{ $row['totalServices'] }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if ($row['totalRepayment'] == 0)
                                    -
                                @else
                                    {{ $row['totalRepayment'] }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if ($row['totalPayment'] == 0)
                                    -
                                @else
                                    {{ $row['totalPayment'] }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if ($row['totalDocuments'] == 0)
                                    &mdash;
                                @else
                                    {{ $row['totalDocuments'] }}
                                @endif
                            </td>
                            <td class="text-right nbr">
                                @if ($row['totalPrice'] == 0)
                                    -
                                @else
                                    {{ number_format($row['totalPrice'], 0, ',', ' ') }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="active">
                        <td><strong>Всего:</strong></td>
                        <td></td>
                        <td class="text-right nbr"><strong>{{ $totalForeigners }}</strong></td>
                        <td class="text-right nbr"><strong>{{ $totalServices }}</strong></td>
                        <td class="text-right nbr"><strong>{{ $totalRepayment }}</strong></td>
                        <td class="text-right nbr"><strong>{{ $totalPayment }}</strong></td>
                        <td class="text-right nbr"><strong>{{ $totalDocuments }}</strong></td>
                        <td class="text-right nbr"><strong>{{ number_format($totalPrice, 0, ',', ' ') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    @endif

    <script>
        window.print();
    </script>
@endsection
