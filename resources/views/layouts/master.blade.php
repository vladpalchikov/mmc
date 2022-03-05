<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow"/>
    <title>@yield('title') - ММЦ Онлайн</title>
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/datatables.min.css">
    <link rel="stylesheet" href="/css/daterangepicker.css">
    <link rel="stylesheet" href="/css/select2.min.css">
    <link rel="stylesheet" href="/css/select2-bootstrap.min.css">
    <link rel="stylesheet" href="/css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="/css/easy-autocomplete.min.css">
    <link rel="stylesheet" href="/css/easy-autocomplete.themes.min.css">
    <link rel="stylesheet" href="/css/main.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/bootstrap-theme.css?v={{ time() }}">
    <link href="/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/favicon.ico" rel="icon" type="image/x-icon">
    @if(Auth::user()->hasRole('managermu|managermusn|managertm|managertmsn|cashier'))
      <style type="text/css" media="print">
        body { visibility: hidden; display: none }
      </style>
    @endif
</head>
<body>
    @if (Auth::user())
        @if (Auth::user()->isImpersonating())
            <nav class="navbar navbar-static-top navbar-impersonate">
                Вы вошли как <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->getRoles()->first()->name }}) <a href="/users/stop" class="ml10" style="color: white">Вернуться</a>
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
                <div class="col-md-12" style="text-align:center">
                    <span class="text-muted">
                        © 2018 ММЦ Онлайн,&nbsp;
                        @if (\MMC\Models\Update::count() > 0)
                            <span data-container="body" data-toggle="popover" data-placement="top" class="text-info" style="cursor:pointer">v{{ \MMC\Models\Update::orderBy('id', 'desc')->first()->version }}</span>
                            <div id="popover-content" class="hide">
                                @foreach (\MMC\Models\Update::orderBy('id', 'desc')->limit(3)->get() as $version)
                                    <!-- <p><span class="label label-info">{{ strftime('%e %B %Y', strtotime($version->created_at)) }}</span></p> -->
                                    <p>{!! $version->update !!}</p>
                                @endforeach
                                <a href="/changelog" class="btn btn-default btn-block btn-sm">История обновлений</a>
                            </div>
                        @endif
                    </span>
                    <a href="" class="btn btn-default btn-up pull-right btn-sm" style="margin-right:22px;">
                        <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    @include('modals.new-foreigner-modal')
    @include('partials.js')
    @if(Auth::user()->hasRole('managermu|managermusn|managertm|managertmsn|managerbg|cashier'))
      <script type="text/javascript">
        $(document).ready(function () {
          $('body').bind('cut copy', function (e) {
            e.preventDefault();
            alert('У вас нет доступа к копированию данных')
          });
          $("body").on("contextmenu",function(e){
            alert('У вас нет доступа к копированию данных')
            return false;
          });
        });
      </script>
    @endif
</body>
</html>
