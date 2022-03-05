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
    <h3>Отчет о регистрации иностранных граждан</h3>

    @if ($hosts)
        @if ($hosts->count() > 0)
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width:100%">Менеджер</th>
                            <th>Принимающая&nbsp;сторона</th>
                            <th class="tw100">Период&nbsp;отчета</th>
                            <th class="tw100">Граждан</th>
                        </tr>
                        <tr>
                            <td>
                                @if (Request::has('manager'))
                                    {{ \MMC\Models\User::find(Request::get('manager'))->name }}
                                @else
                                    {{ Auth::user()->name }}
                                @endif
                            </td>

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
                            <td>{{ $hosts->count() }}</td>
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
                            <th>Иностранный&nbsp;гражданин</th>
                            <th>Дата&nbsp;создания</th>
                            <th>Тип&nbsp;принимающей&nbsp;стороны</th>
                            <th>Название&nbsp;организации&nbsp;/&nbsp;ФИО</th>
                            <th style="width: 200px;">Адрес&nbsp;постановки&nbsp;на&nbsp;учет</th>
                            <th>Менеджер</th>
                        </tr>

                        @foreach ($hosts as $reg)
                            <tr>
                                <td class="text-center">{{ $count++ }}</td>
                                <td><span class="capitalize">{{ $reg->foreigner->surname }} {{ $reg->foreigner->name }} {{ $reg->foreigner->middle_name }}</span></td>
                                <td>{{ $reg->created_at->format('d.m.Y') }}</td>
                                <td>
                                    @if ($reg->host)
                                        @if ($reg->host->type)
                                            Физическое&nbsp;лицо
                                        @else
                                            Юридическое&nbsp;лицо
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if ($reg->host)
                                        {{ $reg->host->name }}
                                    @endif
                                </td>
                                <td class="capitalize nbr" style="width: 200px;">
                                    {{ $reg->foreigner_address }}
                                </td>
                                <td>
                                    @if ($reg->operator)
                                        {{ $reg->operator->name }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        @else
            <div class="alert alert-info text-center" role="alert">Нет заявок</div>
        @endif
    @else
        <div class="alert alert-info text-center" role="alert">Нет заявок</div>
    @endif

    <script>
        window.print();
    </script>
@endsection
