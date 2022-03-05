<div class="form-group normal-group row ml20">
	<div class="col-md-2">
		<label class="control-label">Документ</label>
		<input class="form-control" name="foreigner[{{ $i }}][document]" type="text" value="ПАСПОРТ">
	</div>

	<div class="col-md-2">
		<label class="control-label">Серия</label>
		<input class="form-control" name="foreigner[{{ $i }}][document_series]" type="text" value="">
	</div>

	<div class="col-md-2">
		<label class="control-label">Номер</label>
		<input class="form-control" name="foreigner[{{ $i }}][document_number]" type="text" value="">
	</div>

	<div class="col-md-2">
		<label class="control-label">Фамилия</label>
		<input class="form-control" name="foreigner[{{ $i }}][surname]" type="text" value="">
	</div>

	<div class="col-md-2">
		<label class="control-label">Имя</label>
		<input class="form-control" name="foreigner[{{ $i }}][name]" type="text" value="">
	</div>

	<div class="col-md-2">
		<label class="control-label">Отчество</label>
		<input class="form-control" name="foreigner[{{ $i }}][middle_name]" type="text" value="">
	</div>
</div>