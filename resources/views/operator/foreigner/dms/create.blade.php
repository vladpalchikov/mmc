@extends('layouts.master')

@section('menu')
    @include('partials.operator-menu', ['active' => 'foreigners'])
@endsection

@section('content')
<div class="row">
	<div class="col-md-8">
	  <h3>
	  	@if (isset($foreigner))
			Редактирование полиса ДМС {{ $foreigner->document_series }}{{ $foreigner->document_number }}
	  	@else
	  		Выдача полиса ДМС
	  	@endif
	  </h3>
	</div>
</div>
<div class="row">
    <div class="col-md-8 form-group">
      <div class="row">
        {!! form_start($form) !!}

        {!! form_end($form) !!}
      </div>
    </div>
</div>
@endsection
