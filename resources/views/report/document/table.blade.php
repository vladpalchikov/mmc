<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <tr>
                <th style="width:100%">Подготовил</th>
                <th class="tw100">Период&nbsp;отчета</th>
                <th class="tw100">Количество&nbsp;документов</th>
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
                <td>{{ $documents->count() }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <tr>
                <th width="10%">Тип&nbsp;документа</th>
                <th width="20%">Дата&nbsp;оформления</th>
                <th width="40%">ФИО&nbsp;ИГ</th>
                <th width="10%">Паспорт&nbsp;ИГ</th>
                <th width="20%">Менеджер</th>
            </tr>

            @foreach ($documents as $document)
                <tr>
                    <td>
                        @if ($document instanceof \MMC\Models\ForeignerIg)
                            Прибытие ИГ
                        @elseif ($document instanceof \MMC\Models\ForeignerPatent)
                            Патент
                        @elseif ($document instanceof \MMC\Models\ForeignerPatentChange)
                            Изменение в патенте
                        @elseif ($document instanceof \MMC\Models\ForeignerPatentRecertifying)
                            Переоформление патента
                        @endif
                    </td>
                    <td>{{ date('d.m.Y H:i', strtotime($document->created_at)) }}</td>
                    <td>
                        @if ($document->foreigner)
                            @if ($print)
                                <span class="capitalize">{{ $document->foreigner->surname }} {{ $document->foreigner->name }} {{ $document->foreigner->middle_name }}</span>
                            @else
                                <a href="/operator/foreigners/{{ $document->foreigner_id }}" class="capitalize">{{ $document->foreigner->surname }} {{ $document->foreigner->name }} {{ $document->foreigner->middle_name }}</a>
                            @endif
                        @else
                            &mdash;
                        @endif
                    </td>
                    <td class="uppercase">
                        @if ($document->foreigner)
                            {{ $document->foreigner->document_series }}{{ $document->foreigner->document_number }}
                        @else
                            &mdash;
                        @endif
                    </td>
                    <td>
                        @if ($document->operator)
                            {{ $document->operator->name }}
                        @else
                            &mdash;
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
