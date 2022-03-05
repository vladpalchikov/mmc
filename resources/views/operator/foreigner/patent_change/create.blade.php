@extends('layouts.master')

@section('menu')
    @include('partials.operator-menu', ['active' => 'foreigners'])
@endsection

@section('content')
<div class="row">
	<div class="col-md-8">
	  <h3>
	  	@if (isset($foreigner))
			Редактирование "Заявления о внесении изменений в патент № {{ $foreigner->document_series }}{{ $foreigner->document_number }}"
	  	@else
	  		Новое заявление о внесении изменений в патент
	  	@endif
	  </h3>
	</div>
</div>
<div class="row">
    <div class="col-md-8 form">
		<div class="row">
			{!! form_start($form) !!}
			{!! form_until($form, 'date_change') !!}
			<div class="clearfix">&nbsp;</div>
			{!! form_end($form) !!}
		</div>
    </div>
</div>
@endsection
