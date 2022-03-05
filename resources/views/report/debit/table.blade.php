@if ($totalUnpayedServices > 0)
<div class="row">
    <div class="col-lg-12">
        @if ($print)
            <p class="text-muted"><small>Подготовлено в ММЦ Онлайн {{ date('d.m.Y в H:i') }}</small></p>
            <h3>Отчет по задолженности плательщиков</h3>
        @endif
        <table class="table table-bordered">
            <tr>
                <th style="width: 100%">Область&nbsp;отчета</th>
                <th class="tw100">Период&nbsp;отчета</th>
                <th class="tw100">Плательщик</th>
                <th class="tw100">Услуг&nbsp;не&nbsp;оплачено</th>
                <th class="tw100">Сумма,&nbsp;руб.</th>
            </tr>
            <tr>
                <td>
                    @if (Request::has('mmc'))
                        {{ \MMC\Models\MMC::find(Request::get('mmc'))->name }}
                    @else
                         @if (Auth::user()->hasRole('business.manager|accountant|cashier'))
                            {{ Auth::user()->mmc->name }}
                        @else
                            Все&nbsp;ММЦ
                        @endif
                    @endif
                </td>
                <td class="nbr">
                    @if (count($daterange) > 1)
                        @if ($daterange[0] != $daterange[1])
                            {{ $daterange[0] }} - {{ $daterange[1] }}
                        @else
                            {{ $daterange[0] }}
                        @endif
                    @else
                        {{ $daterange[0] }}
                    @endif
                </td>
                <td class="nbr">
                    @if (Request::has('client'))
                        {{ \MMC\Models\Client::find(Request::get('client'))->name }}
                    @else
                        Все плательщики ({{ count($clients) }})
                    @endif
                </td>
                <td class="nbr">{{ $totalUnpayedServices }}</td>
                <td class="nbr">{{ number_format($totalUnpayedSum, 0, ',', ' ') }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover data-table data-table-services">
            <thead>
                <tr>
                    <th style="width: 100%">Название&nbsp;плательщика</th>
                    <th>Тип</th>
                    <th>ИНН</th>
                    <th class="nbr">Контактное лицо</th>
                    <th class="nbr">Услуг за период</th>
                    <th class="nbr">Услуг не оплачено</th>
                    <th class="nbr">Сумма, руб.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                    <tr>
                        <td class="nbr">
                            @if ($print)
                                {{ $client->name }}
                            @else
                                <a href="/operator/clients/{{ $client->id }}">{{ $client->name }}</a>
                            @endif
                        </td>
                        <td class="nbr">{{ $client->type == 0 ? 'Юридическое лицо' : 'Физическое лицо' }}</td>
                        <td class="nbr">{{ $client->inn }}</td>
                        <td class="nbr capitalize">
                            {{ $client->contact_person }}
                            @if (!empty($client->person_document_phone))
                                ({{ $client->person_document_phone }})
                            @endif

                            @if (!empty($client->organization_contact_phone))
                                ({{ $client->organization_contact_phone }})
                            @endif
                        </td>
                        <td class="text-right nbr">{{ $client->totalServices }}</td>
                        <td class="text-right nbr">{{ $client->unpayedServices }}</td>
                        <td class="text-right nbr"><nobr>{{ number_format($client->unpayedSum, 0, ',', ' ') }}</nobr></td>
                    </tr>
                @endforeach
            </tbody>
            <tr class="active">
                <td colspan="4"><strong>Всего:</strong></td>
                <td class="text-right nbr"><strong>{{ $totalUnpayedServices }}</strong></td>
                <td class="text-right nbr"><strong>{{ number_format($totalUnpayedSum, 0, ',', ' ') }}</strong></td>
            </tr>
        </table>
    </div>
</div>
@else
    <div class="alert alert-info text-center" role="alert">Нет данных</div>
@endif
