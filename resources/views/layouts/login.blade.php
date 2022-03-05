<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow"/>
	<title>ММЦ Онлайн</title>
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/datatables.min.css">
    <link rel="stylesheet" href="/css/daterangepicker.css">
    <link rel="stylesheet" href="/css/select2.min.css">
    <link rel="stylesheet" href="/css/select2-bootstrap.min.css">
    <link rel="stylesheet" href="/css/main.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/bootstrap-theme.css?v={{ time() }}">
    <link href="/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/favicon.ico" rel="icon" type="image/x-icon">
</head>
<body class="login" style="margin-bottom: 85px">
	@if (Auth::user())
		@if (Auth::user()->isImpersonating())
			<nav class="navbar navbar-static-top navbar-impersonate">
				Вы вошли как <strong>{{ Auth::user()->name }}</strong> <a href="/users/stop" class="ml10" style="color: white">Вернуться</a>
			</nav>
		@endif
	@endif
	@yield('menu')
	<div class="container-fluid">
		@yield('content')
	</div>

	<footer class="footer">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12" style="text-align: center"><span class="text-muted">© 2017 ММЦ Онлайн</span></div>
			<div>
		</div>
	</footer>

	@include('modals.new-foreigner-modal')
	@include('partials.js')
</body>
</html>
