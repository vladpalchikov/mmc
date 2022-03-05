@extends('layouts.master')

@section('title', 'Pay QR')

@section('menu')
    @include('partials.admin-menu', ['active' => 'paymentreport'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Статистика платежей</h3>
            {{--
            <a href="/operator/report/payment/print?daterange={{ Request::get('daterange') }}" class="btn btn-default pull-right mt15">Распечатать</a>
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
                    <input type="submit" class="btn btn-primary btn-report" value="Построить отчет">
                </div>
            </div>
        </form>
    </div>

    @include('report.payment.table', ['print' => false])
@endsection
