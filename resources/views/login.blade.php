@extends('layouts.login')
@section('content')

<div class="container-fluid">
	<div class="row">
		<div class="center-block logo">
			<img src="img/logo2.png" alt="" />
			@foreach ($errors->all() as $key => $error)
				<p class="text-warning">{{ $error }}</p>
			@endforeach
			{!! form($form) !!}
		</div>
	</div>
</div>

@endsection
