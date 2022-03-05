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
<body style="background-color: #ecf0f1; margin-bottom: 20px">
	<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">
        Просмотр документа
      </a>
    </div>
		<a href="/operator/clients/{{ $group->client->id }}/" class="btn btn-danger navbar-btn navbar-right">Закрыть</a>&nbsp;
		<a href="" class="btn btn-primary btn-print navbar-btn navbar-right" style="margin-right: 10px">Распечатать</a>
  </div>
</nav>
	<div class="container-fluid">
		@yield('content')
	</div>
	@include('partials.js')
</body>
</html>
