@extends('layouts.master')

@section('title', 'Новая заявка')

@section('menu')
    @include('partials.operator-menu', ['active' => 'foreigners'])
@endsection

@section('content')

@if (isset($foreigner))
    @if ($foreigner->registration_date < date('Y-m-d') || !isset($foreigner->inn))
    <div class="row well mb5">
        <div class="alert alert-warning text-center mb0" style="margin-top: -21px; border-radius: 0" role="alert">
            <h4>Данные устарели или не полные</h4>
            <p>Удостоверьтесь в актуальности данных гражданина перед оказанием услуг, обязательно проверьте наличие <strong>ИНН</strong>, <strong>адрес и дату окончания регистрации</strong> гражданина.</p>
        </div>
    </div>
    @endif
@endif

<div class="row">
	<div class="col-md-8">
	  <h3>
      @if (isset($foreigner))
        Редактирование данных гражданина
      @else
        Новый гражданин
      @endif
    </h3>
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
	<div class="col-md-8 form-group">
		{!! form_start($form) !!}
		<div class="row">
			{!! form_until($form, 'nationality') !!}
			{!! form_until($form, 'gender') !!}
			{!! form_until($form, 'inn') !!}
			<div class="col-md-9 inn-error hide">
				<div class="alert alert-danger"></div>
			</div>
			<div class="col-md-9 inn-error-field hide">
				<div class="alert alert-danger">
					Для получения ИНН необходимо заполнить следующие поля: <strong>фамилия, имя, дата рождения, серия, номер и дата выдачи документа</strong>
				</div>
			</div>
	        {!! form_until($form, 'get_inn') !!}
            <div class="form-group col-md-9 mb5">
                <label for="host_id" class="choices-label">Принимающая сторона</label>
                <select class="clients-ig nos2" id="host_id" name="host_id">
                    @if (isset($data['host_id']))
                        <option value="{{ $data['host_id'] }}">{{ \MMC\Models\Client::find($data['host_id'])->name }}</option>
                    @endif

                    @if (isset($form->getModel()->host_id))
                        <option value="{{ $form->getModel()->host_id }}">{{ \MMC\Models\Client::find($form->getModel()->host_id)->name }}</option>
                    @endif
                </select>
            </div>
			{!! form_until($form, 'address') !!}
			<div class="form-group col-md-9">
			{!! form_widget($form->address_line2) !!}
			</div>
			<div class="form-group col-md-9">
			{!! form_widget($form->address_line3) !!}
			</div>
			<div class="clearfix">&nbsp;</div>
			{!! form_until($form, 'phone') !!}
			<div class="clearfix">&nbsp;</div>
			<div class="col-md-12">
				<h4>Данные для оплаты налога через терминал</h4>
			</div>
			{!! form_until($form, 'ifns_name') !!}
			<div class="col-md-12 oktmo-error hide">
				<div class="alert alert-danger">
					Не удалось получить данные, сервер не доступен
				</div>
			</div>
			<div class="col-md-12 oktmo-data hide">
				<div class="alert alert-info"></div>
				<div class="alert alert-warning">
					Если <strong>адрес регистрации</strong> не совпадает с <strong>адресом ОКТМО</strong>, возможно в нем есть ошибка. Исправьте <strong>адрес регистрации</strong> и запросите ОКТМО повторно или укажите ОКТМО и ИФНС самостоятельно.
				</div>
			</div>
			<div class="clearfix">&nbsp;</div>
			{!! form_until($form, 'oktmo_btn') !!}
			<div class="clearfix">&nbsp;</div>
			<div class="clearfix">&nbsp;</div>

			{!! form_end($form) !!}
		</div>
	</div>
</div>

@include('modals.captcha-modal')
@include('modals.inn-blocked-modal')


@endsection
