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
            @page {size: landscape}
        }
    </style>

    <div class="row mt20">
        <div class="col-md-12">
            <a href="/operator/report/registry?daterange={{ Request::get('daterange') }}&user={{ Request::get('user') }}&mmc={{ Request::get('mmc') }}" class="btn btn-danger pull-right">Закрыть</a>
        </div>
    </div>

    <h3>
      Реестр учета приема заявлений
      @if (count($daterange) > 1)
          @if ($daterange[0] != $daterange[1])
              {{ Helper::dateFromString($daterange[0]) }} - {{ Helper::dateFromString($daterange[1]) }}
          @else
              {{ Helper::dateFromString($daterange[0]) }}
          @endif
      @else
          {{ Helper::dateFromString($daterange[0]) }}
      @endif
    </h3>

    @if ($registry->count() > 0)
        <?php $count = $skip; ?>

        <div class="row">
            <div class="col-md-12">
                @foreach ($registry->chunk(20) as $chunk)
                    <table class="table table-bordered" @if ($registry->count() - $count > 20) style="page-break-after: always;" @endif>
                        <thead>
                            <tr>
                                <th rowspan="2" class="tc35 nbr">П/н</th>
                                <th rowspan="2" class="nbr">ФИО Заявителя</th>
                                <th rowspan="2" class="nbr">Дата рождения</th>
                                <th colspan="3">Документ, удостоверяющий личность</th>
                                <th rowspan="2">Гражданство</th>
                                <th rowspan="2" class="nbr">Внутренний номер</th>
                                <th rowspan="2" class="nbr">ФИО ответственного сотрудника</th>
                                <th rowspan="2"class="nbr">Отметка о передачи</th>
                                <th rowspan="2" class="nbr">Отметка о получении</th>
                            </tr>
                            <tr>
                                <th>серия</th>
                                <th>номер</th>
                                <th class="nbr">дата выдачи</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                              @for ($i = 1; $i < 12; $i++)
                                  <td style="padding: 4px 8px 4px 8px; font-size: 10px">{{ $i }}</td>
                              @endfor
                            </tr>
                            @foreach ($chunk as $document)
                                @if ($document->foreigner)
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td class="capitalize nbr">{{ $document->foreigner->surname }} {{ $document->foreigner->name }} {{ $document->foreigner->middle_name }}</td>
                                        <td>{{ date('d.m.Y', strtotime($document->foreigner->birthday)) }}</td>
                                        <td class="uppercase">
                                          @if ($document->foreigner->document_series)
                                              {{ $document->foreigner->document_series }}
                                          @else
                                              &mdash;
                                          @endif
                                        </td>
                                        <td>{{ $document->foreigner->document_number }}</td>
                                        <td>{{ date('d.m.Y', strtotime($document->foreigner->document_date)) }}</td>
                                        <td>{{ $document->foreigner->nationality }}</td>
                                        <td>&nbsp;</td>
                                        <td>{{ $document->docuser->name }}</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-center">{{ $count++ }}</td>
                                        <td colspan="5" class="text-center"><p>Не найден ИГ</p></td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
                <table style="width: 600px; border-collapse: collapse; page-break-inside: avoid;">
                  <tr><td>
                      <table style="border-spacing: 15px; border-collapse: separate; width: 450px">
                        <tr>
                          <td colspan="3"><strong>УВМ ГУ МВД России по Самарской области</strong></td>
                        </tr>
                        <tr>
                          <td style="width: 150px">&nbsp;</td>
                          <td style="width: 150px">&nbsp;</td>
                          <td style="width: 150px">&nbsp;</td>
                        </tr>
                        <tr>
                          <td style="border-top: 1px solid;">&nbsp;</td><td style="border-top: 1px solid; text-align: center">Подпись</td><td style="border-top: 1px solid; text-align: center">ФИО</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td style="border-top: 1px solid;">&nbsp;</td><td style="border-top: 1px solid; text-align: center">Подпись</td><td style="border-top: 1px solid; text-align: center">ФИО</td>
                        </tr>
                      </table>
                    </td>
                    <td>
                      <table style="border-spacing: 15px; border-collapse: separate; width: 450px">
                        <tr>
                          <td colspan="3"><strong>ООО "ЦПМ"</strong></td>
                        </tr>
                        <tr>
                          <td style="width: 150px">&nbsp;</td>
                          <td style="width: 150px">&nbsp;</td>
                          <td style="width: 150px">&nbsp;</td>
                        </tr>
                        <tr>
                          <td style="border-top: 1px solid;">&nbsp;</td><td style="border-top: 1px solid; text-align: center">Подпись</td><td style="border-top: 1px solid; text-align: center">ФИО</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td style="border-top: 1px solid;">&nbsp;</td><td style="border-top: 1px solid; text-align: center">Подпись</td><td style="border-top: 1px solid; text-align: center">ФИО</td>
                        </tr>
                      </table>
                    </td></tr>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center" role="alert">Нет документов</div>
    @endif
    <script>
        window.print();
    </script>
@endsection
