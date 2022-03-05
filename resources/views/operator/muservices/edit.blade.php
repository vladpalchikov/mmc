@extends('layouts.master')

@section('menu')
    @include('partials.operator-menu', ['active' => 'clients'])
@endsection

@section('content')
<div class="row">
	<div class="col-md-8">
	  <h3>
		Редактирование заявки № {{ $group->id }}
	  </h3>
	  <div>&nbsp;</div>
	</div>
</div>
@if (count($errors) > 0)
<div class="row">
	<div class="col-md-8">
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
	<div class="col-md-12 form-group">
		{!! form_start($form) !!}
		<div class="row">
			<div class="col-md-4">
				{!! form_widget($form->created_at) !!}
			</div>
			<div class="col-md-3">
				{!! form_widget($form->created_at_time) !!}
			</div>
			<div class="col-md-5"><span style="display: inline-block;padding-top: 7px; margin-left: -23px">&mdash; дата и время включения заявки в отчет</span></div>
			<div class="col-md-12">
				<div style="border-bottom: 1px dashed #ccc; margin: 22px 0px 15px 0px"></div>
			</div>

            <div class="col-md-12">
                {!! form_until($form, 'client_id') !!}
            </div>

            <div class="col-md-12">
			    {!! form_until($form, 'payment_method') !!}
            </div>

			<div class="col-md-8">
				<h4>Услуги</h4>
				{{-- <h5 class="pull-right count-title">Количество</h5> --}}
				<div class="form-group choice-group form-group service-group">
					<label for="services[]" class="choices-label"> </label>
					@foreach ($services as $id => $service)
						<div class="col-md-12 pl0 pr0">
				            <input id="services_{{ $id }}"
				            	name="service"
				            	type="radio"
				            	value="{{ $id }}"
				            	@if ($loop->first) checked="checked" @endif
				            	@if ($id == $group->service_id) checked="checked" @endif
				            >

				            <label class="control-label width-80" for="services_{{ $id }}" >
				            	{{ $service->name }} ({{ round($service->price) }} руб.)
				            </label>

				            {{-- <input id="services_count_{{ $id }}"
				            	name="services_count[{{ $id }}]"
				            	type="number"
				            	class="form-control service-count"
				            	data-service-id="{{ $id }}"
				            	style="width: 80px"
				            	value="1"
				            > --}}
			            </div>
					@endforeach
				</div>
			</div>

			<div class="col-md-12">
	            <div><h4>Граждане</h4></div>

				@foreach ($group->services as $service)
					@if ($service->foreigner)
						<input type="hidden" name="old_foreigner[{{ $service->foreigner->id }}][service_id]" value="{{ $service->id }}">
		                <table class="mu-table mb5">
		                    <tr>
								<td style="max-width:120px"><input class="form-control document_name" name="old_foreigner[{{ $service->foreigner->id }}][document_name]" type="text" value="{{ $service->foreigner->document_name }}"></td>
								<td style="max-width:80px"><input class="form-control document_series" name="old_foreigner[{{ $service->foreigner->id }}][document_series]" type="text" value="{{ $service->foreigner->document_series }}" placeholder="Серия" pattern="[a-zA-Zа-яА-Я0-9\-]{0,7}" title="Только цифры и буквы"></td>
								<td style="max-width:100px"><input class="form-control document_number" name="old_foreigner[{{ $service->foreigner->id }}][document_number]" type="text" value="{{ $service->foreigner->document_number }}" placeholder="Номер" placeholder="Номер" pattern="[0-9]{1,11}"></td>
								<td><input class="form-control surname" name="old_foreigner[{{ $service->foreigner->id }}][surname]" type="text" value="{{ $service->foreigner->surname }}" placeholder="Фамилия"></td>
								<td><input class="form-control name" name="old_foreigner[{{ $service->foreigner->id }}][name]" type="text" value="{{ $service->foreigner->name }}" placeholder="Имя"></td>
								<td><input class="form-control middle_name" name="old_foreigner[{{ $service->foreigner->id }}][middle_name]" type="text" value="{{ $service->foreigner->middle_name }}" placeholder="Отчество"></td>
								<td><input class="form-control birthday" name="old_foreigner[{{ $service->foreigner->id }}][birthday]" type="date" value="{{ $service->foreigner->birthday }}"></td>
								<td><input class="form-control nationality" name="old_foreigner[{{ $service->foreigner->id }}][nationality]" placeholder="Гражданство" name="nationality" type="text" value="{{ $service->foreigner->nationality }}"autocomplete="off" spellcheck="false" dir="auto" style="position: relative; vertical-align: top;"></td>
								<td>
									<select class="form-control nos2" name="old_foreigner[{{ $service->foreigner->id }}][type_appeal]">
										<option value="0" @if ($service->type_appeal == 0) selected="selected" @endif>Первичная регистрация</option>
										<option value="1" @if ($service->type_appeal == 1) selected="selected" @endif>Продление</option>
										<option value="2" @if ($service->type_appeal == 2) selected="selected" @endif>Трудовой договор</option>
									</select>
								</td>
		                    </tr>
						</table>
					@endif
				@endforeach
			</div>

			{{-- <div class="col-md-12">
	            <div><h4>Добавить граждан</h4></div>

				<div class="foreigners-services">
	                <table class="mu-table mb5">
	                    <tr>
							<td style="max-width:120px"><input class="form-control document_name" name="foreigner[0][document_name]" type="text" value="ПАСПОРТ"></td>
							<td style="max-width:80px"><input class="form-control document_series" name="foreigner[0][document_series]" type="text" value="" placeholder="Серия"></td>
							<td style="max-width:100px"><input class="form-control document_number" name="foreigner[0][document_number]" type="text" value="" placeholder="Номер"></td>
							<td><input class="form-control surname" name="foreigner[0][surname]" type="text" value="" placeholder="Фамилия"></td>
							<td><input class="form-control name" name="foreigner[0][name]" type="text" value="" placeholder="Имя"></td>
							<td><input class="form-control middle_name" name="foreigner[0][middle_name]" type="text" value="" placeholder="Отчество"></td>
							<td><input class="form-control birthday" name="foreigner[0][birthday]" type="date" value=""></td>
							<td><input class="form-control nationality" name="foreigner[0][nationality]" placeholder="Гражданство" name="nationality" type="text" value=""autocomplete="off" spellcheck="false" dir="auto" style="position: relative; vertical-align: top;"></td>
							<td>
								<select class="form-control" name="foreigner[0][type_appeal]">
									<option value="0">Первичная регистрация</option>
									<option value="1">Продление</option>
									<option value="2">Трудовой договор</option>
								</select>
							</td>
	                    </tr>
					</table>
				</div>
			</div> --}}
            <div class="clearfix">&nbsp;</div>

			<div class="col-md-12 mt20">
				{!! form_end($form) !!}
			</div>
		</div>
	</div>
</div>
@endsection
