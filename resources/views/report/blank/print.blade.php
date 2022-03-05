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
            <a href="/operator/report/blank?daterange={{ Request::get('daterange') }}" class="btn btn-danger pull-right">Закрыть</a>
        </div>
    </div>

    <p class="text-muted"><small>Подготовлено в ММЦ Онлайн {{ date('d.m.Y в H:i') }}</small></p>
    <h3>Строгая отчетность</h3>

    @if ($blanks->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th style="width:100%">Подготовил</th>
                        <th class="tw100">Период&nbsp;отчета</th>
                        <th class="tw100">Количество&nbsp;бланков</th>
                    </tr>
                    <tr>
                        <td>{{ Auth::user()->name }}</td>
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
                        <td>{{ $blanks->count() }}</td>
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
                        <th>Дата&nbsp;оформления</th>
                        <th>ФИО&nbsp;ИГ</th>
                        <th>Паспорт&nbsp;ИГ</th>
                        <th>Номер&nbsp;документа</th>
                        <th>Менеджер</th>
                    </tr>

                    @foreach ($blanks as $blank)
                        <tr>
                            <td class="text-center">{{ $count++ }}</td>
                            <td>{{ $blank->created_at->format('d.m.Y h:m') }}</td>
                            <td><a href="/operator/foreigners/{{ $blank->foreigner_id }}" class="capitalize">{{ $blank->foreigner->surname }} {{ $blank->foreigner->name }} {{ $blank->foreigner->middle_name }}</a></td>
                            <td>{{ $blank->foreigner->document_series }}{{ $blank->foreigner->document_number }}</td>
                            <td>{{ $blank->full_number }}</td>
                            <td>{{ $blank->operator->name }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center" role="alert">Нет бланков</div>
    @endif
    <script>
        window.print();
    </script>
@endsection
