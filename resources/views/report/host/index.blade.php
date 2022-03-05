@extends('layouts.master')

@section('title', 'Регистрация граждан')

@section('menu')
    @include('partials.operator-menu', ['active' => 'hostreport'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Отчет о регистрации иностранных граждан</h3>
            <a href="/operator/report/host/print?daterange={{ Request::get('daterange') }}&@if (Request::has('manager'))&manager={{ Request::get('manager') }}@endif&@if (Request::has('client'))&client={{ Request::get('client') }}@endif" class="btn btn-default pull-right">Распечатать</a>
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
                    @if (Auth::user()->hasRole('administrator|managermusn|accountant|chief.accountant|business.manager'))
                        <div class="form-group">
                            <div class="input-group">
                                <select name="manager" class="form-control pull-right">
                                    <option value="">Все менеджеры</option>
                                    @foreach($operators as $operator)
                                        <option @if(Request::get('manager') == $operator->id) selected @endif @if(!Request::has('manager') && $operator->id == Auth::user()->id) selected @endif value="{{ $operator->id }}">{{ $operator->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    @if (Auth::user()->hasRole('administrator') || Auth::user()->hasRole('administrator|managermusn|accountant|chief.accountant|business.manager'))
                        <div class="form-group">
                            <div class="input-group">
                                <select name="client" class="form-control pull-right">
                                    @foreach($clients as $client)
                                        <option @if(Request::get('client') == $client->id) selected @endif value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
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

    @if ($hosts)
        @if ($hosts->count() > 0)
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width:100%">Менеджер</th>
                            <th>Принимающая&nbsp;сторона</th>
                            <th class="tw100">Период&nbsp;отчета</th>
                            <th class="tw100">Граждан</th>
                        </tr>
                        <tr>
                            <td>
                                @if (Request::has('manager'))
                                    {{ \MMC\Models\User::find(Request::get('manager'))->name }}
                                @else
                                    {{ Auth::user()->name }}
                                @endif
                            </td>

                            <td>
                                @if (Request::has('client'))
                                    {{ \MMC\Models\Client::find(Request::get('client'))->name }}
                                @else
                                    Все
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
                            <td>{{ $hosts->count() }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @include('report.host.table')
        @else
            <div class="alert alert-info text-center" role="alert">Нет заявок</div>
        @endif
    @else
        <div class="alert alert-info text-center" role="alert">Нет заявок</div>
    @endif
@endsection
