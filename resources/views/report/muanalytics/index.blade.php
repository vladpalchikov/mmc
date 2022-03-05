@extends('layouts.master')

@section('title', 'Аналитика МУ')

@section('menu')
    @include('partials.operator-menu', ['active' => 'muanalytics'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Аналитика МУ</h3>
            <a href="/operator/report/muanalytics/print?daterange={{ Request::get('daterange') }}&nationality={{ Request::has('nationality') ? true : '' }}" class="btn btn-default pull-right">Распечатать</a>
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
                    @else
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">Дата</div>
                                <input type="text" name="daterange" data-single="true" class="form-control" value={{ Request::get('daterange') }}>
                            </div>
                        </div>
                    @endif
                    <input type="submit" class="btn btn-primary btn-report" value="Построить отчет">

                    <div class="btn-group pull-right" role="group" aria-label="...">
                        <a href="/operator/report/muanalytics?daterange={{ Request::get('daterange') }}" class="btn btn-default @if (!$is_nationality) btn-primary @endif">Плательщики</a>
                        <a href="/operator/report/muanalytics?daterange={{ Request::get('daterange') }}&nationality=true" class="btn btn-default @if ($is_nationality) btn-primary @endif">Национальность</a>
                    </div>
                    {{-- <input type="submit" class="btn btn-success btn-report" value="Экспорт"> --}}
                    {{-- <div class="form-group"><div class="input-group export"></div></div> --}}
                </div>
            </div>
        </form>
    </div>

    @if (count($nationalityData) == 0)
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                      <th style="width:100%">Менеджер</th>
                      <th>Дата&nbsp;отчета</th>
                      <th>Итого&nbsp;плательщиков</th>
                      <th>Итого&nbsp;ИГ</th>
                      <th>Первичная&nbsp;регистрация</th>
                      <th>Продление</th>
                      <th>Трудовой&nbsp;договор</th>
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
                        <td>{{ $reportData['count_clients'] }}</td>
                        <td>{{ $reportData['count_foreigners'] }}</td>
                        <td>{{ $reportData['count_type_appeal_0'] }}</td>
                        <td>{{ $reportData['count_type_appeal_1'] }}</td>
                        <td>{{ $reportData['count_type_appeal_2'] }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @include('report.muanalytics.table')
    @else
        @include('report.muanalytics.table-nationality')
    @endif
@endsection
