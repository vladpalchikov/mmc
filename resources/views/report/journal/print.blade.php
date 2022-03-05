@extends('layouts.print_report')

@section('content')
    <style>
        * {
            font-size: 11px;
        }
        .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border: 1px solid #000 !important;
        }
        @media print {
            .btn-danger {
                display: none;
            }
            @page {size: landscape}
            thead {
              display: table-row-group;
            }
        }
    </style>

    <div class="row mt20">
        <div class="col-md-12">
            <a href="/operator/report/journal?daterange={{ Request::get('daterange') }}&user={{ Request::get('user') }}&mmc={{ Request::get('mmc') }}" class="btn btn-danger pull-right">Закрыть</a>
        </div>
    </div>

    <h3>
      Журнал учета приема заявлений и выдачи патентов
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

    @if ($journal->count() > 0)
        <?php $count = 1 ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2" class="tc35">№ п/п</th>
                                <th rowspan="2">Код<br>приема</th>
                                <th rowspan="2">Дата<br>приема</th>
                                <th rowspan="2">ФИО<br>Заявителя</th>
                                <th rowspan="2">Дата<br>рождения</th>
                                <th colspan="3">Документ, удостоверяющий личность</th>
                                <th rowspan="2">Адрес&nbsp;постановки&nbsp;на<br>учет по месту пребывания</th>
                                <th rowspan="2">Гражданство</th>
                                <th rowspan="2">Отметка о передачи документов должностному лицу Главного управления  (подпись сотрудника уполномоченной организации)</th>
                                <th rowspan="2">Патент<br>(в случае отказа - основания)</th>
                                <th rowspan="2">Бланк<br>патента</th>
                                <th rowspan="2">Отметка&nbsp;о&nbsp;получении<br>(подпись иностранного гражданина)</th>
                                <th rowspan="2">Отметка&nbsp;о&nbsp;выдаче<br>(подпись должностного лица, дата выдачи)</th>
                            </tr>
                            <tr>
                              <th>серия</th>
                              <th>номер</th>
                              <th>дата<br>выдачи</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                              @for ($i = 1; $i < 6; $i++)
                                  <td style="padding: 4px 8px 4px 8px; font-size: 10px">{{ $i }}</td>
                              @endfor
                                  <td colspan="3" style="padding: 4px 8px 4px 8px; font-size: 10px">6</td>
                              @for ($i = 7; $i < 14; $i++)
                                  <td style="padding: 4px 8px 4px 8px; font-size: 10px">{{ $i }}</td>
                              @endfor
                            </tr>
                        @foreach ($journal as $document)
                                @if ($document->foreigner)
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>&nbsp;</td>
                                        <td>{{ date('d.m.Y', strtotime($document->conf_at)) }}</td>
                                        <td><span class="capitalize">{{ $document->foreigner->surname }} {{ $document->foreigner->name }} {{ $document->foreigner->middle_name }}</span> (ООО “ЦПМ“)</td>
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
                                        <td class="capitalize">{{ $document->foreigner->address }} {{ $document->foreigner->address_line2 }} {{ $document->foreigner->address_line3 }}</td>
                                        <td>{{ $document->foreigner->nationality }}</td>
                                        <td>{{ $document->docuser->name }} (ООО “ЦПМ“)</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
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
                        <tr>
                          <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td>
                      <table style="border-spacing: 15px; border-collapse: separate; width: 450px">
                        <tr>
                          <td colspan="3"><strong>&nbsp;</strong></td>
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
                        <tr>
                          <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td style="border-top: 1px solid;">&nbsp;</td><td style="border-top: 1px solid; text-align: center">Подпись</td><td style="border-top: 1px solid; text-align: center">ФИО</td>
                        </tr>
                      </table>
                    </td></tr>
                </table>
              </td>
            </table>
    @else
        <div class="alert alert-info text-center" role="alert">Нет документов</div>
    @endif
    <script>
        window.print();
    </script>
@endsection
