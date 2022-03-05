@extends('layouts.master')

@section('title', 'Нераспознанные платежи')

@section('menu')
    @include('partials.admin-menu', ['active' => 'unrecognize'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Нераспознанные платежи</h3>
        </div>
    </div>

    <div class="well mt20">
        <form class="form-inline">
            <div class="row mb20">
                <div class="col-md-4">
                    <div class="pull-left">
                        <input type="submit" class="btn btn-primary ml10 pull-right" value="Поиск">
                        <input type="text" name="search" placeholder="Номер платежа или документа" class="form-control pull-right" value="{{ Request::get('search') }}" style="width: 250px;">
                    </div>
                </div>
            </div>
        </form>
    </div>

    @include('report.tax.table', ['print' => false, 'unrecognize' => true])

    @include('modals.unrecognize-modal')

@endsection
