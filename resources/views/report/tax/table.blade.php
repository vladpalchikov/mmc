@if (true)
<div class="row">
    <div class="col-lg-12">
        @if ($print)
            <p class="text-muted"><small>Подготовлено в ММЦ Онлайн {{ date('d.m.Y в H:i') }}</small></p>
            <h3>История платежей</h3>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Внутренний&nbsp;номер</th>
                    <th>Номер</th>
                    <th>Чек</th>
                    <th>ИНН</th>
                    <th>Документ</th>
                    <th>Плательщик</th>
                    <th>Название&nbsp;налога</th>
                    <th>К&nbsp;оплате,&nbsp;руб.</th>
                    <th>Оплачено,&nbsp;руб.</th>
                    <th>Статус</th>
                    <th>Создан</th>
                    <th>Обновлен</th>
                    <th>ФИО&nbsp;/&nbsp;Адрес&nbsp;плательщика</th>
                    @if ($unrecognize)
                        <th></th>
                    @endif
                </tr>
            </thead>
            @php
                $taxPriceSum = 0;
            @endphp
            <tbody>
            @foreach ($qrs as $qr)
                <tr data-trqr="{{ $qr->id }}">
                    <td>
                        @if ($qr->txn_id)
                            {{ $qr->txn_id }}
                        @else
                            Импорт
                        @endif
                    </td>
                    <td>
                        @if ($qr->transaction)
                            {{ $qr->transaction }}
                        @else
                            Без&nbsp;номера
                        @endif
                    </td>
                    <td>
                        @if ($qr->receipt_id)
                            {{ $qr->receipt_id }}
                        @else
                            Без&nbsp;номера
                        @endif
                    </td>
                    <td>

                        {{--
                        @if ($qr->foreigner)
                            @if ($qr->inn)
                                <span @if ($qr->foreigner->inn == 0 && !$qr->foreigner->inn_check) class="text-danger" @endif>{{ $qr->inn }}</span>
                            @else
                                <span @if ($qr->foreigner->inn == 0 && !$qr->foreigner->inn_check) class="text-danger" @endif>{{ $qr->inn }}</span>
                            @endif

                            @if ($qr->inn == "" && $qr->foreigner->inn == "")
                                &mdash;
                            @endif

                            @if ($qr->inn !== $qr->foreigner->inn)
                                <span title="Q: {{ $qr->inn }} F: {{ $qr->foreigner->inn }}">?</span>
                            @endif
                        @else
                            @if ($qr->inn)
                                {{ $qr->inn }}
                            @else
                                &mdash;
                            @endif
                        @endif
                        --}}

                        @if ($qr->foreigner)
                            @if ($qr->foreigner->inn == 0 && !$qr->foreigner->inn_check)
                                <abbr class="text-danger" title="{{ $qr->inn }} - ИНН не проверен">{{ $qr->foreigner->inn }}</abbr
                            @elseif ($qr->inn !== $qr->foreigner->inn)
                                <abbr class="text-danger" title="{{ $qr->inn }} - ИНН введен на терминале">{{ $qr->foreigner->inn }}</abbr>
                            @else
                                <span>{{$qr->foreigner->inn}}</span>
                            @endif
                        @else
                            @if ($qr->inn)
                                <abbr class="text-danger" title="{{ $qr->inn }} - ИНН введен на терминале">&mdash;</abbr>
                            @else
                                &mdash;
                            @endif
                        @endif

                    </td>
                    <td>
                        @if ($qr->foreigner)
                            @if (Illuminate\Support\Str::upper($qr->foreigner->document_series.$qr->foreigner->document_number) == $qr->document)
                            <abbr title="{{$qr->document}} - документ на чеке совпадает">
                                {{Illuminate\Support\Str::upper($qr->foreigner->document_series)}}{{$qr->foreigner->document_number}}
                            </abbr>
                            @else
                            <abbr class="text-danger" title="{{$qr->document}} - документ на чеке не совпадает">
                                {{Illuminate\Support\Str::upper($qr->foreigner->document_series)}}{{$qr->foreigner->document_number}}
                            </abbr>
                            @endif
                        @else
                            <abbr class="text-danger" title="Тут что-то не так">{{$qr->document}}</abbr>
                        @endif
                    </td>
                    <td>
                        @if ($print)
                            {{ $qr->foreigner->surname }} {{ $qr->foreigner->name }} {{ $qr->foreigner->middle_name }}
                        @else
                            @if ($qr->foreigner)
                                <a href="/operator/foreigners/{{ $qr->foreigner_id }}" class="capitalize">{{ $qr->foreigner->surname }} {{ $qr->foreigner->name }} {{ $qr->foreigner->middle_name }}</a>
                            @else
                                &mdash;
                            @endif
                        @endif
                    </td>
                    <td>
                        @if (isset($qr->tax))
                            {{ $qr->tax->name }}
                        @else
                            &mdash;
                        @endif
                    </td>
                    <td class="text-right nbr">
                        @if (isset($qr->sum) && !empty($qr->sum))
                            {{ number_format((float) $qr->sum, 2, ',', ' ') }}
                        @else
                            &mdash;
                        @endif
                    </td>
                    <td class="text-right nbr">
                        @if (isset($qr->sum_from) && !empty($qr->sum_from))
                            {{ number_format((float) $qr->sum_from, 2, ',', ' ') }}
                        @else
                            &mdash;
                        @endif
                    </td>
                    <td>{{ $qr->getStatus() }}</td>
                    <td class="nbr">
                      @if (!$qr->status_datetime)
                          {{ date('d.m.Y H:i', strtotime($qr->created_at)) }}
                      @else
                        <abbr title="Занесен в систему: {{ date('d.m.Y H:i', strtotime($qr->status_datetime)) }} (Москва)">{{ date('d.m.Y H:i', strtotime($qr->created_at)) }}</abbr>
                      @endif
                    </td>
                    <td class="nbr">{{ date('d.m.Y H:i', strtotime($qr->updated_at)) }}</td>
                    <td class="small" style="text-transform: capitalise">{{ $qr->fio }}<br>{{ $qr->address }}</td>
                    @if ($unrecognize)
                        <td class="nbr">
                            <a class="btn btn-primary btn-sm unrecognize-link" data-id="{{ $qr->id }}">
                                <span class="glyphicon glyphicon-link" aria-hidden="true"></span>
                            </a>
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="pull-right">
        <div class="col-md-12">
            {{ $qrs->appends(request()->input())->links() }}
        </div>
    </div>
</div>
@else
    <div class="alert alert-info text-center" role="alert">Нет данных</div>
@endif
