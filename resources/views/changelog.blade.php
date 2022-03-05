@extends('layouts.master')

@if (Auth::user()->hasRole('admin'))
    @section('menu')
        @include('partials.admin-menu', ['active' => ''])
    @endsection
@else
    @section('menu')
        @include('partials.operator-menu', ['active' => ''])
    @endsection
@endif

@section('content')
	<div class="row">
        <div class="col-md-6">
            @if (count($updates) > 0)
                @foreach ($updates as $update)
                    <p>{!! $update->update !!}</p>
                    <hr>
                @endforeach
            @endif
        </div>
    </div>
@endsection
