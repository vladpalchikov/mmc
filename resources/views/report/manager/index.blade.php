@extends('layouts.master')

@section('title', 'Сводный отчет')

@section('menu')
    @include('partials.operator-menu', ['active' => 'managerreport'])
@endsection

@section('content')
    <div class="row hide-print">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Сводный отчет</h3>
            <a href="" class="btn btn-default pull-right btn-print">Распечатать</a>
        </div>
    </div>

    <div class="well mt20 filters">
        <form class="form-inline">
            <div class="row">
                <div class="col-md-12">
                    @if (Auth::user()->hasRole('administrator|managertmsn|accountant|chief.accountant|business.manager|business.managerbg'))
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
                    @if (Auth::user()->hasRole('administrator|chief.accountant|business.manager|business.managerbg') && $mmc->count() > 1)
                        <div class="form-group">
                            <div class="input-group">
                                <select name="mmc" class="form-control pull-right" style="min-width:150px">
                                    @foreach($mmc as $item)
                                        <option @if($currentMMC == $item->id) selected @endif value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <input type="submit" class="btn btn-primary btn-report" value="Построить отчет">
                </div>
            </div>
        </form>
    </div>

    @include('report.manager.table', ['print' => false])
@endsection
