@extends('layouts.master')

@section('title', 'Сводный отчет')

@section('menu')
    @include('partials.operator-menu', ['active' => 'documentreport'])
@endsection

@section('content')
    <div class="row hide-print">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Отчет о документах</h3>
            <a href="/operator/report/document/print?daterange={{ Request::get('daterange') }}&type={{ Request::get('type') }}&mmc={{ Request::get('mmc') }}&user={{ Request::get('user') }}" class="btn btn-default pull-right">Распечатать</a>
        </div>
    </div>

    <div class="well mt20 filters">
        <form class="form-inline">
            <div class="row">
                <div class="col-md-12">
                    @if (Auth::user()->hasRole('administrator|managertmsn|accountant|chief.accountant|business.manager'))
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

                    @if (Auth::user()->hasRole('administrator|chief.accountant|business.manager'))
                        <div class="form-group">
                            <div class="input-group">
                                <select name="user" class="form-control pull-right" style="min-width:250px">
                                    <option value="">Все менеджеры</option>
                                    @foreach($users as $user)
                                        <option @if(Request::get('user') == $user->id) selected @endif value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    @if (Auth::user()->hasRole('administrator|business.manager'))
                        <div class="form-group">
                            <div class="input-group">
                                <select name="type" class="form-control pull-right" style="min-width:250px">
                                    <option value="">Все документы</option>
                                    <option value="1" @if (Request::get('type') == 1) selected="selected" @endif>Оформление патента</option>
                                    <option value="2" @if (Request::get('type') == 2) selected="selected" @endif>Переоформление патента</option>
                                    <option value="3" @if (Request::get('type') == 3) selected="selected" @endif>Внесение изменений в патент</option>
                                    <option value="4" @if (Request::get('type') == 4) selected="selected" @endif>Прибытие ИГ</option>
                                </select>
                            </div>
                        </div>
                    @endif

                    <input type="submit" class="btn btn-primary btn-report" value="Построить отчет">
                </div>
            </div>
        </form>
    </div>

    @if ($documents->count() > 0)
        @include('report.document.table', ['print' => false])
    @else
        <div class="alert alert-info text-center" role="alert">Нет документов</div>
    @endif
@endsection
