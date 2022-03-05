@extends('layouts.master')

@section('title', 'Новая услуга')

@section('menu')
    @if (Auth::user()->hasRole('admin'))
        @include('partials.admin-menu', ['active' => 'services'])
    @else
        @include('partials.operator-menu', ['active' => ''])
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h3 class="pull-left">
                @if (isset($service))
                    Редактирование услуги "{{ $service->name }}"
                @else
                    Новая услуга
                @endif
            </h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form">
            {!! form_start($form) !!}
            {!! form_until($form, 'is_complex') !!}
            <div class="complex @if (!$service->is_complex) hide @endif mt20">
                @foreach ($companies as $company)
                    <div class="row">
                        <div class="col-md-3"><div class="mt5">{{ $company->name }}</div></div>
                        <div class="col-md-3">
                            <input class="form-control" name="complex[{{ $company->id }}]" type="text" placeholder="Цена" value="{{ $service->getCompanyComplex($company->id) }}">
                        </div>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                @endforeach
            </div>
            {!! form_end($form) !!}
        </div>
    </div>
@endsection
