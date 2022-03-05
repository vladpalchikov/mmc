@extends('layouts.master')

@section('title', 'Отчет МУ')

@section('menu')
    @include('partials.operator-menu', ['active' => 'mureport'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(Auth()->user()->hasRole('managermu|managermusn'))
                <h3 class="pull-left main-title main-title-single">Отчет о заявках</h3>
            @else
                <h3 class="pull-left main-title main-title-single">Отчет о заявках на услуги МУ</h3>
            @endif
            <a href="/operator/report/mu/print?daterange={{ Request::get('daterange') }}&client={{ Request::get('client') }}&manager={{ Request::get('manager') }}" class="btn btn-default pull-right">Распечатать</a>
        </div>
    </div>

    <div class="well mt20">
        <form class="form-inline">
            <div class="row">
                <div class="col-md-12">
                    @if (Auth::user()->hasRole('administrator|managermusn|accountant|chief.accountant|business.manager'))
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">Дата</div>
                                <input type="text" name="daterange" data-single="false" class="form-control" value={{ $daterange[0] }}-{{ $daterange[1] }}>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <select name="manager" class="form-control pull-right" style="min-width: 200px">
                                    <option value="">Все менеджеры</option>
                                    @foreach($operators as $operator)
                                        <option @if(Request::get('manager') == $operator->id) selected @endif @if(!Request::has('manager') && $operator->id == Auth::user()->id) selected @endif value="{{ $operator->id }}">{{ $operator->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <select name="client" class="form-control pull-right" style="min-width: 300px">
                                    <option value="">Все плательщики</option>
                                    @foreach($clients as $client)
                                        <option @if(Request::get('client') == $client->id) selected @endif value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @else
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">Дата</div>
                                <input type="text" name="daterange" data-single="true" class="form-control" value={{ Request::get('daterange') }}>
                            </div>
                        </div>
                    @endif
                    <input type="submit" class="btn btn-primary btn-report" value="Построить отчет">
                    <input type="submit" class="btn btn-success btn-report" value="Экспорт">
                    <div class="form-group"><div class="input-group export"></div></div>
                </div>
            </div>
        </form>
    </div>
    @if (count($reportServices) > 0)
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th style="width:100%">Менеджер</th>
                        <th class="tw100">Период&nbsp;отчета</th>
                        <th class="tw100">Плательщиков</th>
                        <th class="tw100">Документов</th>
                        <th class="tw100">Услуг&nbsp;(ФЛ)</th>
                        <th class="tw100">Услуг&nbsp;(ЮЛ)</th>
                        <th class="tw100">Итого&nbsp;(ФЛ),&nbsp;руб.</th>
                        <th class="tw100">Итого&nbsp;(ЮЛ),&nbsp;руб.</th>
                    </tr>
                    <tr>
                        <td>
                            @if (Request::has('manager'))
                                {{ \MMC\Models\User::find(Request::get('manager'))->name }}
                            @else
                                @if (Auth::user()->hasRole('managermu'))
                                    {{ Auth::user()->name }}
                                @else
                                    Все менеджеры
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
                        <td>{{ $reportServices->unique('client_id')->count() }}</td>
                        <td>{{ $totalDocuments }}</td>
                        <td>{{ $totalIndividualServices }}</td>
                        <td>{{ $totalLegalServices }}</td>
                        <td><nobr>{{ number_format($totalIndividualPrice, 0, ',', ' ') }}</nobr></td>
                        <td><nobr>{{ number_format($totalLegalPrice, 0, ',', ' ') }}</nobr></td>
                    </tr>
                </table>
            </div>
        </div>

        @include('report.mu.table')
    @else
        <div class="alert alert-info text-center" role="alert">Нет заявок</div>
    @endif
@endsection
