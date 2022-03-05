@extends('layouts.master')

@if (isset($user))
    @section('title', 'Редактирование пользователя "'.$user->name.'"')
@else
    @section('title', 'Новый пользователь')
@endif

@section('menu')
    @if (Auth::user()->hasRole('admin'))
        @include('partials.admin-menu', ['active' => 'users'])
    @else
        @include('partials.operator-menu', ['active' => ''])
    @endif
@endsection

@section('content')
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
        <div class="col-md-6">
            <h3 class="pull-left">
                @if (isset($user))
                    Редактирование пользователя "{{ $user->name }}"
                @else
                    Новый пользователь
                @endif
            </h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form">
            {!! form($form) !!}
        </div>
    </div>
@endsection
