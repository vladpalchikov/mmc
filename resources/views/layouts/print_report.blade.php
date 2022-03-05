<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>ММЦ Онлайн</title>
	<link rel="stylesheet" href="/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/datatables.min.css">
	<link rel="stylesheet" href="/css/main.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/bootstrap-theme.css">
    <link href="/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/favicon.ico" rel="icon" type="image/x-icon">
</head>
<body>
	<div class="container-fluid">
		@yield('content')
	</div>
	@include('partials.js')
</body>
</html>
