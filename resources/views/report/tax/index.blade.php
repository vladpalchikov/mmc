@extends('layouts.master')

@section('title', 'История платежей')

@section('menu')
    @include('partials.admin-menu', ['active' => 'taxreport'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">История платежей</h3>
            {{--
            <a href="/operator/report/tax/print?daterange={{ Request::get('daterange') }}" class="btn btn-default pull-right mt15">Распечатать</a>
            --}}
        </div>
    </div>

    <div class="well mt20">
        <form class="form-inline">
            <div class="row mb20">
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Дата</div>
                            <input type="text" name="daterange" data-single="false" class="form-control" value={{ $daterange[0] }}-{{ $daterange[1] }}>
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="status">
                            <option @if (!Request::has('status')) selected="selected" @endif value="">Все статусы</option>
                            <option @if (Request::get('status') == 1) selected="selected" @endif  value="1">Оплаченные</option>
                            <option @if (Request::get('status') == 2) selected="selected" @endif  value="2">Возвращенные</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="tax">
                            <option @if (!Request::has('tax')) selected="selected" @endif value="">Все налоги</option>
                            <option @if (Request::get('tax') == 'null') selected="selected" @endif value="null">Не определен</option>
                            @foreach ($taxes as $tax)
                                <option @if (Request::get('tax') == $tax->id) selected="selected" @endif value="{{ $tax->id }}">{{ $tax->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="submit" class="btn btn-primary btn-report" value="Построить отчет">
                </div>

                <div class="col-md-4">
                    <div class="pull-right">
                        <input type="submit" class="btn btn-primary ml10 pull-right" value="Поиск">
                        <input type="text" name="search" placeholder="Номер платежа или документа" class="form-control pull-right ml10" value="{{ Request::get('search') }}" style="width: 250px;">
                    </div>
                </div>
            </div>
        </form>
    </div>

    @include('report.tax.table', ['print' => false, 'unrecognize' => false])
@endsection
