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
            <a href="#" onclick="window.history.back()" class="btn btn-danger pull-right">Закрыть</a>
        </div>
    </div>

    <p class="text-muted"><small>Подготовлено в ММЦ Онлайн {{ date('d.m.Y в H:i') }}</small></p>
    <h3>Отчет о принимающих сторонах</h3>

    @if ($clients->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th style="width:100%">Принимающая&nbsp;сторона</th>
                        <th class="tw100">Период&nbsp;отчета</th>
                        <th class="tw100">Граждан</th>
                        <th class="tw100">Принимающих&nbsp;сторон</th>
                    </tr>
                    <tr>
                        <td>
                            @if (Request::has('client'))
                                {{ \MMC\Models\Client::find(Request::get('client'))->name }}
                            @else
                                Все
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
                        <td>{{ $clients->sum('foreigner_count') }}</td>
                        <td>{{ $clients->count() }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <?php $count = 1 ?>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th class="tc35 text-center">№</th>
                        <th>Название&nbsp;организации&nbsp;/&nbsp;ФИО</th>
                        <th>Тип&nbsp;принимающей&nbsp;стороны</th>
                        <th>Граждан</th>
                    </tr>

                    @foreach ($clients as $client)
                        <tr>
                            <td class="text-center">{{ $count++ }}</td>
                            <td>{{ $client->name }}</td>
                            <td>
                                @if ($client->type)
                                    Физическое&nbsp;лицо
                                @else
                                    Юридическое&nbsp;лицо
                                @endif
                            </td>
                            <td>{{ $client->foreigner_count }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif
    <script>
        window.print();
    </script>
@endsection
