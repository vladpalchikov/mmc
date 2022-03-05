@extends('layouts.master')

@section('title', 'Журнал учета приема заявлений и выдачи патентов')

@section('menu')
    @include('partials.operator-menu', ['active' => 'journalreport'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Журнал учета приема заявлений и выдачи патентов
              @if (count($daterange) > 1)
                  @if ($daterange[0] != $daterange[1])
                      {{ Helper::dateFromString($daterange[0]) }} - {{ Helper::dateFromString($daterange[1]) }}
                  @else
                      {{ Helper::dateFromString($daterange[0]) }}
                  @endif
              @else
                  {{ Helper::dateFromString($daterange[0]) }}
              @endif
              (Документов: {{ $journal->count() }})
            </h3>
            <a href="/operator/report/journal/print?daterange={{ Request::get('daterange') }}&mmc={{ Request::get('mmc') }}@if (Request::has('recertifying'))&recertifying=true @endif" class="btn btn-default pull-right">Распечатать</a>
        </div>
    </div>

    <div class="well mt20">
        <form class="form-inline">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Дата</div>
                            <input type="text" name="daterange" data-single="false" class="form-control" value={{ $daterange[0] }}-{{ $daterange[1] }}>
                        </div>
                    </div>

                    @if (Auth::user()->hasRole('administrator|chief.accountant'))
                        <div class="form-group">
                            <div class="input-group">
                                <select name="mmc" class="form-control pull-right" style="min-width:150px">
                                    <option value="">Все ММЦ</option>
                                    @foreach($mmc as $item)
                                        <option @if(Request::get('mmc') == $item->id) selected @endif value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    @if (Request::has('recertifying'))
                        <input type="hidden" name="recertifying" value="true">
                    @endif

                    <input type="submit" class="btn btn-primary btn-report" value="Построить отчет">
                    <input type="submit" class="btn btn-success btn-report" value="Экспорт">
                    <div class="form-group"><div class="input-group export"></div></div>
                </div>
            </div>
        </form>
    </div>

    <div class="row well mt20">
        <div class="col-md-4">
            <input type="text" name="search" id="customSearch" autocomplete="off" autofocus placeholder="Поиск по журналу" class="form-control" value="{{ Request::get('search') }}" style="width:100%;">
        </div>
        <div class="col-md-8">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a href="/operator/report/journal?daterange={{ Request::get('daterange') }}" class="btn btn-default @if (!Request::has('recertifying')) btn-primary @endif">Выдача патента</a>
                <a href="/operator/report/journal?daterange={{ Request::get('daterange') }}&recertifying=true" class="btn btn-default @if (Request::has('recertifying')) btn-primary @endif">Переоформление патента</a>
            </div>
        </div>
    </div>

    @include('report.journal.table')
@endsection
