@extends('layouts.master')

@section('title', 'Менеджеры')

@section('menu')
    @include('partials.operator-menu', ['active' => 'summaryreport'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Отчет о работе менеджеров</h3>
        </div>
    </div>
    <div class="well mt20">
        <form class="form-inline">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Дата</div>
                    <input type="text" name="daterange" data-single="false" class="form-control" value={{ $daterange[0] }}-{{ $daterange[1] }}>
                </div>
            </div>
            @if (!Auth::user()->hasRole('business.manager|accountant'))
                <div class="form-group">
                    <div class="input-group">
                        <select name="mmc" class="form-control pull-right" style="min-width:150px">
                            @foreach($mmc as $item)
                                <option @if(Request::get('mmc') == $item->id) selected @endif value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <input type="submit" class="btn btn-primary btn-report" value="Построить отчет">
            <input type="submit" class="btn btn-success btn-report" value="Экспорт">
            <div class="form-group"><div class="input-group export"></div></div>
        </form>
    </div>

    @if ($totalPayment > 0)
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 100%">Область&nbsp;отчета</th>
                        <th class="tw100">Период&nbsp;отчета</th>
                        <th class="tw100">Граждан</th>
                        <th class="tw100">Услуг&nbsp;оплачено</th>
                        <th class="tw100">Услуг&nbsp;оплачено&nbsp;(комп.)</th>
                        <th class="tw100">Итого,&nbsp;руб.</th>
                    </tr>
                    <tr>
                        <td>
                            @if (Request::has('mmc'))
                                {{ \MMC\Models\MMC::find(Request::get('mmc'))->name }}
                            @else
                                @if (Auth::user()->hasRole('business.manager|accountant'))
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
                        <td class="nbr">{{ $totalForeigners }}</td>
                        <td class="nbr">{{ $totalPayment }}</td>
                        <td class="nbr">{{ $totalPaymentComplex }}</td>
                        <td class="nbr">{{ number_format($totalPrice, 0, ',', ' ') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @include('report.summary.table')
    @else
        <div class="alert alert-info text-center" role="alert">Нет заявок</div>
    @endif
@endsection
