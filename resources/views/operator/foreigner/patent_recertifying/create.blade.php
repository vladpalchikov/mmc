@extends('layouts.master')

@section('menu')
    @include('partials.operator-menu', ['active' => 'foreigners'])
@endsection

@section('content')
<div class="row">
	<div class="col-md-8">
	  <h3>
	  	@if (isset($foreigner))
			Редактирование "Заявление на переоформление патента № {{ $foreigner->document_series }}{{ $foreigner->document_number }}"
	  	@else
	  		Новое заявление
	  	@endif
	  </h3>
	</div>
</div>
<div class="row">
    <div class="col-md-8 form">
		<div class="row">
			{!! form_start($form) !!}
			{!! form_end($form) !!}
		</div>
    </div>
</div>
@endsection
