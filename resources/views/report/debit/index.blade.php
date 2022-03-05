@extends('layouts.master')

@section('menu')
    @include('partials.operator-menu', ['active' => 'debitreport'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Отчет по задолженности плательщиков</h3>
            <a href="/operator/report/debit/print?daterange={{ Request::get('daterange') }}&client={{ Request::get('client') }}&mmc={{ Request::get('mmc') }}" class="btn btn-default pull-right">Распечатать</a>
        </div>
    </div>

    <div class="well mt20">
        <form class="form-inline">
            <div class="row">
                <div class="col-md-12">
                    @if (Auth::user()->hasRole('administrator|accountant|chief.accountant|business.manager'))
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
                                        <option value="">Все&nbsp;юниты</option>
                                        @foreach($mmc as $item)
                                            <option @if(Request::get('mmc') == $item->id) selected @endif value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                    @endif
                    @if (Auth::user()->hasRole('cashier') && !Auth::user()->hasRole('administrator'))
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">Дата</div>
                                <input type="text" name="daterange" data-single="true" class="form-control" value={{ Request::get('daterange') }}>
                            </div>
                        </div>
                    @endif
                    @if (Auth::user()->hasRole('administrator') || Auth::user()->hasRole('accountant|chief.accountant'))
                        <div class="form-group">
                            <div class="input-group">
                                <select name="client" class="form-control pull-right">
                                    <option value="">Все плательщики</option>
                                    @foreach($clients as $client)
                                        <option @if(Request::get('client') == $client->id) selected @endif value="{{ $client->id }}">{{ $client->name }}</option>
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

    @include('report.debit.table', ['print' => false])
@endsection
