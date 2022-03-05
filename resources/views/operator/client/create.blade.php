@extends('layouts.master')

@section('title',  'Новый представитель')

@section('menu')
    @include('partials.operator-menu', ['active' => 'clients'])
@endsection

@section('content')
    <h3>Данные о представителе</h3>
    @if (count($errors) > 0)
        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-6 form">
            <div class="row">
                {!! form($form) !!}
            </div>
        </div>
    </div>
@endsection
