<?php $count = 1 ?>
@if ($journal->count() > 0)
    <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table table-bordered data-table" id="customSearchTable">
                <thead>
                    <tr>
                        <th class="tc35">№</th>
                        <th>Код<br>приема</th>
                        <th>Дата&nbsp;приема</th>
                        <th>ФИО&nbsp;Заявителя</th>
                        <th>Дата&nbsp;рождения</th>
                        <th>Серия</th>
                        <th>Номер</th>
                        <th>Дата&nbsp;выдачи</th>
                        <th>Адрес постановки на учет по месту пребывания</th>
                        <th>Гражданство</th>
                        <th>Отметка о передачи документов должностному лицу Главного управления  (подпись сотрудника уполномоченной организации)</th>
                        <th>Патент<br>(в случае отказа - основания)</th>
                        <th>Бланк<br>патента</th>
                        <th>Отметка&nbsp;о&nbsp;получении<br>(подпись иностранного гражданина)</th>
                        <th>Отметка&nbsp;о&nbsp;выдаче<br>(подпись должностного лица, дата выдачи)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($journal as $document)
                        @if ($document->foreigner)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>&nbsp;</td>
                                <td>{{ date('d.m.Y', strtotime($document->conf_at)) }}</td>
                                <td>
                                  <a href="/operator/foreigners/{{ $document->foreigner_id }}">
                                    {{ title_case($document->foreigner->surname) }} {{ title_case($document->foreigner->name) }} {{ title_case($document->foreigner->middle_name) }}
                                  </a> (ООО “ЦПМ“)
                                </td>
                                <td>{{ date('d.m.Y', strtotime($document->foreigner->birthday)) }}</td>
                                <td class="uppercase">
                                  @if ($document->foreigner->document_series)
                                      {{ mb_strtoupper($document->foreigner->document_series) }}
                                  @else
                                      &mdash;
                                  @endif
                                </td>
                                <td>{{ $document->foreigner->document_number }}</td>
                                <td>{{ date('d.m.Y', strtotime($document->foreigner->document_date)) }}</td>
                                <td>{{ $document->foreigner->address }} {{$document->foreigner->address_line2 }} {{ $document->foreigner->address_line3 }}</td>
                                <td>{{ title_case($document->foreigner->nationality) }}</td>
                                <td>{{ $document->docuser->name }}</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center">{{ $count++ }}</td>
                                <td colspan="7" class="text-center"><p class="text-danger">Не найден ИГ</p></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
      </div>
    </div>
@else
    <div class="alert alert-info text-center" role="alert">Нет документов</div>
@endif
