@if ($stats['payments_count'] > 0)
<div class="row">
    <div class="col-lg-12">
        @if ($print)
            <p class="text-muted"><small>Подготовлено в ММЦ Онлайн {{ date('d.m.Y в H:i') }}</small></p>
            <h3>Отчет по входящим платежам</h3>
        @endif
    </div>
</div>

<div class="panel panel-default panel-inline">
    <div class="panel-heading">
        <h3 class="panel-title">Плательщиков</h3>
    </div>
    <div class="panel-body">
        <h2>{{ number_format($stats['payers_count'], 0, ',', ' ') }}</h2>
        <div class="panel-sub-header text-muted">
            @if ($qrs->where('foreigner_id', '<>', null)->count() > 0)
                {{ $stats['payers_count_with_inn'] }} ({{ number_format($stats['payers_count_with_inn_percent'], 0) }}%) с ИНН
            @else
                0 с ИНН
            @endif
        </div>
    </div>
</div>

<div class="panel panel-default panel-inline">
    <div class="panel-heading">
        <h3 class="panel-title">Платежей</h3>
    </div>
    <div class="panel-body">
        <h2>{{ number_format($stats['payments_count'], 0, ',', ' ') }}</h2>
        <div class="panel-sub-header text-muted">{{ $stats['payments_count_with_inn'] }} ({{ number_format($stats['payments_count_with_inn_percent'], 0) }}%) с ИНН</div>
    </div>
</div>

<div class="panel panel-default panel-inline">
    <div class="panel-heading">
        <h3 class="panel-title">Вернувшиеся плательщики</h3>
    </div>
    <div class="panel-body">
        <!-- <h2>{{ number_format($stats['returned_percent'], 2) }}%</h2> -->
        <h2>{{ number_format(($stats['returned_count'] / $stats['payers_count'])*100, 2) }}%</h2>
        <div class="panel-sub-header text-muted">{{ $stats['returned_count'] }} плательщиков</div>
    </div>
</div>

<div class="panel panel-default panel-inline">
    <div class="panel-heading">
        <h3 class="panel-title">Повторные платежи</h3>
    </div>
    <div class="panel-body">
        <!-- <h2>{{ number_format($stats['repeated_payments_count_percent'], 2, '.', ' ') }}</h2> -->
        <h2>{{ number_format(($stats['repeated_payments_count'] / $stats['payments_count'])*100, 2) }}%</h2>
        <div class="panel-sub-header text-muted">{{ $stats['repeated_payments_count'] }} повторных платежей</div>
    </div>
</div>

<div class="panel panel-default panel-inline">
    <div class="panel-heading">
        <h3 class="panel-title">Платежей на плательщика</h3>
    </div>
    <div class="panel-body">
        <h2>{{ number_format($stats['payments_count'] / $stats['payers_count'], 2, '.', ' ') }}</h2>
        <div class="panel-sub-header text-muted">&nbsp;</div>
    </div>
</div>

<div class="panel panel-default panel-inline">
    <div class="panel-heading">
        <h3 class="panel-title">Возвраты</h3>
    </div>
    <div class="panel-body">
        <h2>{{ $stats['repayments_count'] }}</h2>
        <div class="panel-sub-header text-muted">{{ number_format($stats['repayments_sum'], 2, ',', ' ') }} руб.</div>
    </div>
</div>

<div class="panel panel-default panel-inline">
    <div class="panel-heading">
        <h3 class="panel-title">Нераспознанные</h3>
    </div>
    <div class="panel-body">
        <h2>{{ $stats['unrecognized'] }}</h2>
        <div class="panel-sub-header text-muted">&nbsp;</div>
    </div>
</div>

<div class="panel panel-warning panel-inline">
    <div class="panel-heading">
        <h3 class="panel-title">Оплачено, руб.</h3>
    </div>
    <div class="panel-body">
        <h2>{{ number_format($stats['paid_sum'], 2, ',', ' ') }}</h2>
        <div class="panel-sub-header text-muted">
            {{ number_format($stats['payments_count'] - $stats['repayments_count'], 0, ',', ' ') }} пл. (ср. чек: {{ number_format($stats['paid_sum_avg'], 2, ',', ' ') }} руб.)
        </div>
    </div>
</div>

<div class="panel panel-warning panel-inline">
    <div class="panel-heading">
        <h3 class="panel-title">Сумма налогов, руб.</h3>
    </div>
    <div class="panel-body">
        <h2>{{ number_format($stats['tax_paid_sum'], 2, ',', ' ') }}</h2>
        <div class="panel-sub-header text-muted">&nbsp;</div>
    </div>
</div>

<div class="panel panel-success panel-inline">
    <div class="panel-heading">
        <h3 class="panel-title">Выручка агента, руб.</h3>
    </div>
    <div class="panel-body">
        <h2>{{ number_format($stats['agent_sum'], 2, ',', ' ') }}</h2>
        <div class="panel-sub-header text-muted">
            <!-- {{ number_format($stats['agent_sum_client'], 2, ',', ' ') }} руб. (на клиента) -->
            {{ number_format($stats['agent_sum'] / $stats['payers_count'], 2, ',', ' ') }} руб. (на клиента)
        </div>
    </div>
</div>

<div style="float: none; clear: both"></div>

<div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Авансовый платеж НДФЛ по суммам</h3>
    </div>
<div class="nbr" style="overflow: scroll; width: 100%">
        <style>
            .pl {
                background: #95a5a6;
                display: inline-block;
                min-width: 150px;
                padding: 10px 15px;
                border-radius: 2px;
                margin-right: -8px;
                box-shadow: -2px 0px 3px rgba(0, 0, 0, .2);
                color: white;
            }
            .pl-3101 {
                background-color: #1abc9c;
            }
            .pl-6201 {
                background-color: #f39c12;
            }
            .pl-9302 {
                background-color: #e74c3c;
            }
            .pl-12402 {
                background-color: #8e44ad;
            }
        </style>
        @foreach ($sum as $sumFrom => $count)
                    <div class="pl pl-{{ number_format($sumFrom, 0, ',', '') }}" style="width:{{ number_format((($count * 100) / $stats['payments_count'])/1, 2) }}%">
                    <strong>{{ number_format($sumFrom, 2, ',', ' ') }}</strong> /
                    {{ number_format(($count * 100) / $stats['payments_count'], 2) }}%<br>
                    <span class="small-text">{{ number_format($count, 0, ',', ' ') }} платежей</span>
                    </div>
        @endforeach

</div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Детализация по типам</h3>
    </div>
        <table class="table table-bordered table-hover">
            <tr>
                <th style="width:100%">Налог</th>
                <th class="nbr" style="min-width:180px">Платежей</th>
                <th class="nbr" style="min-width:180px">Сумма возвратов, руб.</th>
                <th class="nbr" style="min-width:180px">Оплачено, руб.</th>
                <th class="nbr" style="min-width:180px">Сумма налогов, руб.</th>
            </tr>
            <tr>
                <td><a href="/operator/report/tax?daterange={{ $daterange[0] }}-{{ $daterange[1] }}&tax=null">Тип не определен</a></td>
                <td>{{ number_format($qrs->where('tax_id', null)->count(), 0, ',', ' ') }}</td>
                <td>{{ number_format($qrs->where('tax_id', null)->where('status', 2)->sum('sum_from'), 2, ',', ' ') }}<br>
                    <span class="text-muted">{{ $qrs->where('tax_id', null)->where('status', 2)->count() }} возвратов</span>
                </td>
                <td>{{ number_format($qrs->where('tax_id', null)->where('status', 1)->sum('sum_from'), 2, ',', ' ') }}<br>
                    <span class="text-muted">{{ $qrs->where('tax_id', null)->where('status', 1)->count() }} платежей</span>
                </td>
                <td>{{ number_format($qrs->where('tax_id', null)->sum('sum'), 2, ',', ' ') }}</td>
            </tr>
            @foreach ($taxes as $tax)
                <tr>
                    <td><a href="/operator/report/tax?daterange={{ $daterange[0] }}-{{ $daterange[1] }}&tax={{ $tax->id }}">{{ $tax->name }}</a></td>
                    <td>{{ number_format($qrs->where('tax_id', $tax->id)->count(), 0, ',', ' ') }}</td>
                    <td>{{ number_format($qrs->where('tax_id', $tax->id)->where('status', 2)->sum('sum_from'), 2, ',', ' ') }}<br>
                        <span class="text-muted">{{ $qrs->where('tax_id', $tax->id)->where('status', 2)->count() }} возвратов</span>
                    </td>
                    <td>{{ number_format($qrs->where('tax_id', $tax->id)->where('status', 1)->sum('sum_from'), 2, ',', ' ') }}<br>
                        <span class="text-muted">{{ $qrs->where('tax_id', $tax->id)->where('status', 1)->count() }} платежей</span>
                    </td>
                    <td>{{ number_format($qrs->where('tax_id', $tax->id)->sum('sum'), 2, ',', ' ') }}</td>
                </tr>
            @endforeach
        </table>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Авансовый платеж НДФЛ по суммам</h3>
    </div>
        <table class="table table-bordered table-hover">
            <tr>
                <th class="nbr">Сумма, руб.</th>
                <th class="nbr" style="width:100%">Количество</th>
            </tr>
            @foreach ($sum as $sumFrom => $count)
                <tr>
                    <td class="nbr">{{ number_format($sumFrom, 2, ',', ' ') }}</td>
                    <td class="nbr">
                        {{ number_format(($count * 100) / $stats['payments_count'], 2) }}%<br>
                        <span class="text-muted">{{ number_format($count, 0, ',', ' ') }} платежей</span>
                    </td>
                </tr>
            @endforeach
        </table>
</div>

{{--
<div class="row">
    <div class="col-md-12">
        <h2>Платежи</h2>
        <table class="table table-bordered">
            <tr>
                <th>Количество&nbsp;платежей</th>
                <th>К&nbsp;оплате,&nbsp;руб.</th>
                <th>Уплачено,&nbsp;руб.</th>
                <th>Количество&nbsp;платежей&nbsp;без&nbsp;ИНН</th>
                <th>Количество&nbsp;возвратов</th>
            </tr>
            <tr>
                <td>{{ $qrs->count() }}</td>
                <td>{{ number_format($qrs->sum('sum'), 2, ',', ' ') }}</td>
                <td>{{ number_format($qrs->sum('sum_from'), 2, ',', ' ') }}</td>
                <td>{{ $qrs->where('inn', 0)->count() }} / {{ number_format($qrs->where('inn', 0)->count()/$qrs->count() * 100, 0) }}%</td>
                <td>{{ $qrs->where('status', 2)->count() }} / {{ number_format($qrs->where('status', 2)->sum('sum'), 2, ',', ' ') }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h2>Плательщики</h2>
        <table class="table table-bordered">
            <tr>
                <th>Общее&nbsp;количество&nbsp;плательщиков</th>
                <th>Среднее&nbsp;количество&nbsp;платежей&nbsp;на&nbsp;одного&nbsp;плательщика</th>
                <th>Количество&nbsp;плательщиков&nbsp;без&nbsp;ИНН</th>
            </tr>
            <tr>
                <td>{{ $qrs->where('foreigner_id', '<>', null)->count() }}</td>
                <td>{{ number_format($qrs->count()/$qrs->where('foreigner_id', '<>', null)->count(), 2, '.', ' ') }}</td>
                <td>{{ \MMC\Models\Foreigner::whereIn('id', $qrs->where('foreigner_id', '<>', null)->pluck('foreigner_id'))->where('inn', 0)->count() }} / {{ number_format(\MMC\Models\Foreigner::whereIn('id', $qrs->where('foreigner_id', '<>', null)->pluck('foreigner_id'))->where('inn', 0)->count()/$qrs->where('foreigner_id', '<>', null)->count() * 100, 0) }}%</td>
            </tr>
        </table>
    </div>
</div>
--}}

@else
    <div class="alert alert-info text-center" role="alert">Нет данных</div>
@endif
