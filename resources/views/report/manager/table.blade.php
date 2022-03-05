@if (true)

<div class="row">
    <div class="col-lg-12">
        <div class="print">
            <p class="text-muted"><small>Подготовлено в ММЦ Онлайн {{ date('d.m.Y в H:i') }}</small></p>
            <h3>Сводный отчет</h3>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 100%">Область&nbsp;отчета</th>
                <th class="tw100">Период&nbsp;отчета</th>
                <th class="tw100 nbr">Граждан</th>
                <th class="tw100 nbr">Новых&nbsp;граждан</th>
                <th class="tw100 nbr">Услуг&nbsp;оплачено&nbsp;(наличные)</th>
                <th class="tw100 nbr">Услуг&nbsp;оплачено&nbsp;(безнал)</th>
                <th class="tw100 nbr">Услуг&nbsp;оплачено</th>
            </tr>
            <tr>
                <td>
                    @if ($currentMMC)
                        {{ \MMC\Models\MMC::find($currentMMC)->name }}
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
                          {{ Helper::dateFromString($daterange[0]) }} - {{ Helper::dateFromString($daterange[1]) }}
                      @else
                          {{ Helper::dateFromString($daterange[0]) }}
                      @endif
                  @else
                      {{ Helper::dateFromString($daterange[0]) }}
                  @endif
                </td>
                <td class="nbr">{{ $totalStats['count_clients_total'] }}</td>
                <td class="nbr">{{ $totalStats['count_new_clients_total'] }}</td>
                <td class="nbr">{{ $totalStats['count_payed_cash_total'] }} ({{ number_format($totalStats['paid_cash_total'], 0, ',', ' ') }} руб.)</td>
                <td class="nbr">{{ $totalStats['count_payed_cashless_total'] }} ({{ number_format($totalStats['paid_cashless_total'], 0, ',', ' ') }} руб.)</td>
                <td class="nbr">{{ $totalStats['count_services_by_users_total'] }} ({{ number_format($totalStats['paid_total'], 0, ',', ' ') }} руб.)</td>
            </tr>
        </table>
    </div>
</div>

<div class="table-responsive">
<table class="table table-hover table-header-rotated">
    <thead>
        <tr>
            <th></th>
            @foreach ($users as $user)
                <th class="rotate">
                    <div>
                        @if ($print)
                            <a>{{ $user['name'] }}</a>
                        @else
                            <a target="_blank" href="/operator/report/tm?daterange={{ Request::get('daterange') }}&manager={{ $user['id'] }}">{{ $user['name'] }}</a>
                        @endif
                    </div>
                </th>
            @endforeach
            <th class="rotate" colspan="2">&nbsp;</th>
            <th class="last"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($services as $service)
            <tr>
                @php
                    $totalRow = 0;
                @endphp
                <th class="row-header"><span class="nbr tx-owr">{{ $service['name'] }}</span></th>
                @foreach ($users as $user)
                    <td>
                        @if (isset($managerServices[$service['id']][$user['id']]))
                            {{ $managerServices[$service['id']][$user['id']] }}
                            @php
                                $totalRow += $managerServices[$service['id']][$user['id']];
                            @endphp
                        @else
                            &mdash;
                        @endif
                    </td>
                @endforeach
                <td colspan="2" class="nbr">{{ $totalRow }}</td>
                <td>&nbsp;</td>
            </tr>
        @endforeach
        <tr class="active">
            <th><strong>Услуг оплачено</strong></th>
            @foreach ($users as $user)
                <td class="text-right nbr">
                    <strong>
                        @if (isset($totalStats['count_services_by_user'][$user['id']]))
                            {{ $totalStats['count_services_by_user'][$user['id']] }}
                        @else
                            &mdash;
                        @endif
                    </strong>
                </td>
            @endforeach
            <td colspan="2" class="nbr"><strong>{{ $totalStats['count_services_by_users_total'] }}</strong></td>
            <td class="text-right nbr">&nbsp;</td>
        </tr>

        <tr class="active">
            <th><strong>&mdash; Услуг оплачено (наличные)</strong></th>
            @foreach ($users as $user)
                <td class="text-right nbr">
                    <strong>
                        @if (isset($totalStats['count_payed_cash'][$user['id']]))
                            {{ $totalStats['count_payed_cash'][$user['id']] }}
                        @else
                            &mdash;
                        @endif
                    </strong>
                </td>
            @endforeach
            <td colspan="2" class="nbr"><strong>{{ $totalStats['count_payed_cash_total'] }}</strong></td>
            <td class="text-right nbr">&nbsp;</td>
        </tr>

        <tr class="active">
            <th><strong>&mdash; Услуг оплачено (безнал)</strong></th>
            @foreach ($users as $user)
                <td class="text-right nbr">
                    <strong>
                        @if (isset($totalStats['count_payed_cashless'][$user['id']]))
                            {{ $totalStats['count_payed_cashless'][$user['id']] }}
                        @else
                            &mdash;
                        @endif
                    </strong>
                </td>
            @endforeach
            <td colspan="2" class="nbr"><strong>{{ $totalStats['count_payed_cashless_total'] }}</strong></td>
            <td class="text-right nbr">&nbsp;</td>
        </tr>

        <tr class="active">
            <th><strong>Возвратов</strong></th>
            @foreach ($users as $user)
                <td class="text-right nbr">
                    <strong>
                        @if (isset($totalStats['count_repayments'][$user['id']]))
                            {{ $totalStats['count_repayments'][$user['id']] }}
                        @else
                            &mdash;
                        @endif
                    </strong>
                </td>
            @endforeach
            <td colspan="2" class="nbr"><strong>{{ $totalStats['count_repayments_total'] }}</strong></td>
            <td class="text-right nbr">&nbsp;</td>
        </tr>

        <tr class="active">
            <th><strong>Граждан принято</strong></th>
            @foreach ($users as $user)
                <td class="text-right nbr">
                    <strong>
                        @if (isset($totalStats['count_clients'][$user['id']]))
                            {{ $totalStats['count_clients'][$user['id']] }}
                        @else
                            &mdash;
                        @endif
                    </strong>
                </td>
            @endforeach
            <td colspan="2" class="nbr"><strong>{{ $totalStats['count_clients_total'] }}</strong></td>
            <td class="text-right nbr">&nbsp;</td>
        </tr>

        <tr class="active">
            <th><strong>Заявок оформлено</strong></th>
            @foreach ($users as $user)
                <td class="text-right nbr">
                    <strong>
                        @if (isset($totalStats['count_all_services_by_user'][$user['id']]))
                            {{ $totalStats['count_all_services_by_user'][$user['id']] }}
                        @else
                            &mdash;
                        @endif
                    </strong>
                </td>
            @endforeach
            <td colspan="2" class="nbr"><strong>{{ $totalStats['count_all_services_by_users_total'] }}</strong></td>
            <td class="text-right nbr">&nbsp;</td>
        </tr>

        <tr class="active">
            <th><strong>Документов</strong></th>
            @foreach ($users as $user)
                <td class="text-right nbr">
                    <strong>
                        @if (isset($totalStats['count_documents'][$user['id']]))
                            {{ $totalStats['count_documents'][$user['id']] }}
                        @else
                            &mdash;
                        @endif
                    </strong>
                </td>
            @endforeach
            <td colspan="2" class="nbr"><strong>{{ $totalStats['count_documents_total'] }}</strong></td>
            <td class="text-right nbr">&nbsp;</td>
        </tr>

        <tr class="active">
            <th class="nbr"><strong>Итого оплачено, т. руб.</strong></th>
            @foreach ($users as $user)
                <td class="text-right nbr">
                    <strong>
                        @if (isset($totalStats['paid'][$user['id']]))
                            {{ number_format($totalStats['paid'][$user['id']]/1000, 1, ',', ' ') }}
                        @else
                            &mdash;
                        @endif
                    </strong>
                </td>
            @endforeach
            <td colspan="2" class="nbr"><strong>{{ number_format($totalStats['paid_total']/1000, 1, ',', ' ') }}</strong></td>
            <td class="text-right nbr">&nbsp;</td>
        </tr>

        <tr class="active">
            <th class="nbr"><strong>&mdash; Итого оплачено (наличные), т. руб.</strong></th>
            @foreach ($users as $user)
                <td class="text-right nbr">
                    <strong>
                        @if (isset($totalStats['paid_cash'][$user['id']]))
                            {{ number_format($totalStats['paid_cash'][$user['id']]/1000, 1, ',', ' ') }}
                        @else
                            &mdash;
                        @endif
                    </strong>
                </td>
            @endforeach
            <td colspan="2" class="nbr"><strong>{{ number_format($totalStats['paid_cash_total']/1000, 1, ',', ' ') }}</strong></td>
            <td class="text-right nbr">&nbsp;</td>
        </tr>

        <tr class="active">
            <th class="nbr"><strong>&mdash; Итого оплачено (безнал), т. руб.</strong></th>
            @foreach ($users as $user)
                <td class="text-right nbr">
                    <strong>
                        @if (isset($totalStats['paid_cashless'][$user['id']]))
                            {{ number_format($totalStats['paid_cashless'][$user['id']]/1000, 1, ',', ' ') }}
                        @else
                            &mdash;
                        @endif
                    </strong>
                </td>
            @endforeach
            <td colspan="2" class="nbr"><strong>{{ number_format($totalStats['paid_cashless_total']/1000, 1, ',', ' ') }}</strong></td>
            <td class="text-right nbr">&nbsp;</td>
        </tr>
    </tbody>
</table>
</div>

@else
    <div class="alert alert-info text-center" role="alert">Нет данных</div>
@endif
