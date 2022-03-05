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
        <a href="/operator/report/muanalytics?daterange={{ Request::get('daterange') }}&nationality={{ Request::has('nationality') ? true : '' }}" class="btn btn-danger pull-right">Закрыть</a>
    </div>

    <div class="row mt20">
        <div class="col-lg-12">
            <p class="text-muted"><small>Подготовлено в ММЦ Онлайн {{ date('d.m.Y в H:i') }}</small></p>
            <h3 class="pull-left">Аналитика МУ</h3>
        </div>
    </div>

    @if (count($nationalityData) == 0)
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th style="width:100%">Менеджер</th>
                        <th>Дата&nbsp;отчета</th>
                        <th>Итого&nbsp;плательщиков</th>
                        <th>Итого&nbsp;ИГ</th>
                        <th>Первичная&nbsp;регистрация</th>
                        <th>Продление</th>
                        <th>Трудовой&nbsp;договор</th>
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
                        <td>{{ $reportData['count_clients'] }}</td>
                        <td>{{ $reportData['count_foreigners'] }}</td>
                        <td>{{ $reportData['count_type_appeal_0'] }}</td>
                        <td>{{ $reportData['count_type_appeal_1'] }}</td>
                        <td>{{ $reportData['count_type_appeal_2'] }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @include('report.muanalytics.table')
    @else
        @include('report.muanalytics.table-nationality')
    @endif

    <script>
        window.print();
    </script>
@endsection
