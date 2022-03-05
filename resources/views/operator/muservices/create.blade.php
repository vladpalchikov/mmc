@extends('layouts.master')

@section('menu')
    @include('partials.operator-menu', ['active' => 'clients'])
@endsection

@section('content')
<div class="row">
	<div class="col-md-8">
	  <h3>Новая заявка</h3>
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
				<div class="form-group choice-group form-group service-group">
					<label for="services[]" class="choices-label"> </label>
					@foreach ($services as $id => $service)
						<div class="col-md-12 pl0 pr0">
				            <input id="services_{{ $id }}"
				            	name="service"
				            	type="radio"
				            	value="{{ $id }}"
				            	@if ($loop->first) checked="checked" @endif
				            >

				            <label class="control-label width-80" for="services_{{ $id }}" >
				                {{ $service->name }} ({{ round($service->price) }} руб.)
				            </label>
			            </div>
					@endforeach
				</div>
			</div>

			<div class="col-md-12">
	            <div class="row">
	            	<div class="col-md-12">
	            		<h4 class="pull-left">Граждане (<span class="count-foreigner">1</span>)</h4>
	            	</div>
	            </div>

				<div class="foreigners-services pull-left mt20">
	                <table class="mu-table mb5">
	                    <tr>
							<td style="max-width:120px"><input class="form-control document_name" name="foreigner[0][document_name]" type="text" value="ПАСПОРТ" required="required"></td>
							<td style="max-width:80px"><input class="form-control document_series" name="foreigner[0][document_series]" type="text" value="" placeholder="Серия" pattern="[a-zA-Zа-яА-Я0-9\-]{0,7}" title="Только цифры и буквы"></td>
							<td style="max-width:100px"><input class="form-control document_number" name="foreigner[0][document_number]" type="text" required="required" value="" placeholder="Номер" pattern="[a-zA-Zа-яА-Я0-9\-]{1,11}" title="Только цифры и буквы"></td>
							<td><input class="form-control surname" name="foreigner[0][surname]" type="text" value="" required="required" placeholder="Фамилия"></td>
							<td><input class="form-control name" name="foreigner[0][name]" type="text" value="" required="required" placeholder="Имя"></td>
							<td><input class="form-control middle_name" name="foreigner[0][middle_name]" type="text" value="" placeholder="Отчество"></td>
							<td><input class="form-control birthday" name="foreigner[0][birthday]" required="required" type="date" value=""></td>
							<td><input class="form-control nationality" name="foreigner[0][nationality]" required="required" placeholder="Гражданство" name="nationality" type="text" value=""autocomplete="off" spellcheck="false" dir="auto" style="position: relative; vertical-align: top;"></td>
							<td>
								<select class="form-control nos2" name="foreigner[0][type_appeal]">
									<option value="0">Первичная регистрация</option>
									<option value="1">Продление</option>
									<option value="2">Трудовой договор</option>
								</select>
							</td>
							<td><a href="" class="btn btn-danger btn-sm btn-ig-remove ml10"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
	                    </tr>
					</table>
				</div>
			</div>
            <div class="col-md-12">
                <a href="" class="btn btn-default btn-add-muservice pull-left mt5" data-service-id="{{ $id }}">Добавить</a>
            </div>
            <div class="clearfix">&nbsp;</div>
			<div class="col-md-12 mt20">
				{!! form_end($form) !!}
			</div>
		</div>
    </div>
</div>
@endsection
