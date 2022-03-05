@extends('layouts.master')

@section('title', 'Патенты')

@section('menu')
    @include('partials.operator-menu', ['active' => 'patentexport'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Патенты</h3>
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
                        <div class="form-group">
                            <div class="input-group">
                                <select name="payments" class="form-control pull-right" style="min-width:150px">
                                    <option value="0" @if(Request::get('payments') == 0) selected @endif>Оплаченные</option>
                                    <option value="1" @if(Request::get('payments') == 1) selected @endif>Все</option>
                                </select>
                            </div>
                        </div>
                    @endif
                    @if (Auth::user()->hasRole('cashier') && !Auth::user()->hasRole('administrator'))
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">Дата</div>
                                <input type="text" name="daterange" data-single="true" class="form-control" value={{ Request::get('daterange') }}>
                            </div>
                        </div>
                    @endif
                    <input type="submit" class="btn btn-primary btn-report" value="Построить отчет">
                    <a href="/operator/export/patent/export?daterange={{ Request::get('daterange') }}&mmc={{ Request::get('mmc') }}&payments={{ Request::get('payments') }}" class="btn btn-success export-btn">Скачать в excel ({{ $patents->total() }})</a>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12">
            @include('export.patent.table', ['export' => false])
        </div>
    </div>

    <div class="row">
        <div class="pull-right">
            <div class="col-md-12">
                {{ $patents->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection
