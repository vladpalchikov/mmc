@if (Auth::check())
<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/"><img src="/img/logo2.png" width="126px" height="20px"></a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                @if(Auth::user()->hasRole('administrator|managertm|managertmsn|managerbg|cashier|business.manager|business.managerbg|accountant|chief.accountant'))
                    <li @if ($active == 'current') class="active" @endif><a href="/operator/current">Заявки</a></li>
                @endif

                @if(Auth::user()->hasRole('administrator|managermu|managermusn|cashier|business.manager|accountant|chief.accountant'))
                    <li @if ($active == 'muservices') class="active" @endif><a href="/operator/muservices">МУ</a></li>
                @endif

                @if(Auth::user()->hasRole('administrator|chief.accountant|accountant|managertm|managertmsn|managerbg'))
                    <li @if ($active == 'cashless') class="active" @endif><a href="/operator/cashless">Б/Н</a></li>
                @endif

                @if(Auth::user()->hasRole('managermu|managermusn'))
                    <li @if ($active == 'cashless') class="active" @endif><a href="/operator/cashless?module=1">Б/Н</a></li>
                @endif

                @if(Auth::user()->hasRole('administrator|cashier'))
                    <li @if ($active == 'cash') class="active" @endif><a href="/operator/cash">Касса</a></li>
                @endif

                <li @if ($active == 'foreigners') class="active" @endif><a href="/operator/foreigners">Граждане</a></li>

                @if(Auth::user()->hasRole('administrator|managermu|managermusn|managertm|managertmsn|accountant|chief.accountant|business.manager|business.managerbg|managerbg'))
                    <li @if ($active == 'clients') class="active" @endif><a href="/operator/clients">Представители</a></li>
                @endif

                <li @if ($active == 'services') class="active" @endif><a href="/operator/service">Услуги</a></li>

                @if(Auth::user()->hasRole('managerbg'))
                    <li @if ($active == 'report') class="active" @endif><a href="/operator/report/tm">Отчет о заявках</a></li>
                @endif

                @if(Auth::user()->hasRole('managertm|managertmsn'))
                    <li @if ($active == 'report') class="active" @endif><a href="/operator/report/tm">Отчет о заявках</a></li>
                    <li @if ($active == 'documentreport') class="active" @endif><a href="/operator/report/document">Отчет о документах</a></li>
                @endif

                @if(Auth::user()->hasRole('managermu|managermusn'))
                    <li @if ($active == 'mureport') class="active" @endif><a href="/operator/report/mu">Отчет о заявках</a></li>
                    <li @if ($active == 'documentreport') class="active" @endif><a href="/operator/report/document">Отчет о документах</a></li>
                @endif

                @if(Auth::user()->hasRole('cashier'))
                    <li @if ($active == 'cashboxreport') class="active" @endif><a href="/operator/report/cashbox">Отчет по кассе</a></li>
                @endif
                @if (Auth::user()->is_have_access_registry)
                  <li @if ($active == 'registryreport') class="active" @endif><a href="/operator/report/registry">Реестр</a></li>
                  <li @if ($active == 'journalreport') class="active" @endif><a href="/operator/report/journal">Журнал</a></li>
                @endif
            </ul>

            @if(Auth::user()->hasRole('administrator|accountant|chief.accountant|business.manager'))
                <ul class="nav navbar-nav">
                  <li class="dropdown dropdown-report-menu">
                    <a href="#" class="dropdown-toggle dropdown-report" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Аналитика&nbsp;<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li @if ($active == 'managerreport') class="active" @endif><a href="/operator/report/manager">Сводный отчет</a></li>
                        <li @if ($active == 'report') class="active" @endif><a href="/operator/report/tm">Отчет ТМ</a></li>
                        <li @if ($active == 'mureport') class="active" @endif><a href="/operator/report/mu">Отчет МУ</a>
                        <li @if ($active == 'muanalytics') class="active" @endif><a href="/operator/report/muanalytics">Аналитика МУ</a></li>
                        <li role="separator" class="divider"></li>
                        <li @if ($active == 'cashboxreport') class="active" @endif><a href="/operator/report/cashbox">Услуги</a></li>
                        <li @if ($active == 'refundreport') class="active" @endif><a href="/operator/report/refund">Возвраты услуг</a></li>
                        <li role="separator" class="divider"></li>
                        <li @if ($active == 'registrationreport') class="active" @endif><a href="/operator/report/registration">Принимающие стороны</a></li>
                        <li @if ($active == 'hostreport') class="active" @endif><a href="/operator/report/host">Регистрация ИГ</a></li>
                        @if(Auth::user()->hasRole('administrator'))
                            <li role="separator" class="divider"></li>
                            <li @if ($active == 'summaryreport') class="active" @endif><a href="/operator/report/summary">Менеджеры</a></li>
                            <li @if ($active == 'debitreport') class="active" @endif><a href="/operator/report/debit">Плательщики</a></li>
                        @endif
                        <li role="separator" class="divider"></li>
                        <li @if ($active == 'blankreport') class="active" @endif><a href="/operator/report/blank">Строгая отчетность</a></li>
                        <li @if ($active == 'documentreport') class="active" @endif><a href="/operator/report/document">Документы</a></li>
                    </ul>
                  </li>
                </ul>
            @endif

            @if(Auth::user()->hasRole('business.managerbg'))
                <ul class="nav navbar-nav">
                  <li class="dropdown dropdown-report-menu">
                    <a href="#" class="dropdown-toggle dropdown-report" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Аналитика&nbsp;<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li @if ($active == 'managerreport') class="active" @endif><a href="/operator/report/manager">Сводный отчет</a></li>
                        <li @if ($active == 'report') class="active" @endif><a href="/operator/report/tm">Заявки</a></li>
                        <li @if ($active == 'cashboxreport') class="active" @endif><a href="/operator/report/cashbox">Услуги</a></li>
                        <li @if ($active == 'refundreport') class="active" @endif><a href="/operator/report/refund">Возвраты услуг</a></li>
                    </ul>
                  </li>
                </ul>
            @endif

            @if(Auth::user()->hasRole('managermusn'))
                <ul class="nav navbar-nav">
                  <li class="dropdown dropdown-report-menu">
                    <a href="#" class="dropdown-toggle dropdown-report" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Аналитика&nbsp;<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li @if ($active == 'registrationreport') class="active" @endif><a href="/operator/report/registration">Принимающие стороны</a></li>
                        <li @if ($active == 'hostreport') class="active" @endif><a href="/operator/report/host">Регистрация ИГ</a></li>
                        <li @if ($active == 'muanalytics') class="active" @endif><a href="/operator/report/muanalytics">Аналитика МУ</a></li>
                    </ul>
                  </li>
                </ul>
            @endif

            @if(Auth::user()->hasRole('administrator|accountant|chief.accountant|business.manager'))
                <ul class="nav navbar-nav">
                  <li class="dropdown dropdown-export-menu">
                    <a href="#" class="dropdown-toggle dropdown-export" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Экспорт&nbsp;<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li @if ($active == 'patentexport') class="active" @endif><a href="/operator/export/patent">Патенты</a></li>
                    </ul>
                  </li>
                </ul>
            @endif
            <ul class="nav navbar-nav navbar-right">
            <li class="dropdown dropdown-logout-menu">
              <a href="#" class="dropdown-toggle dropdown-logout" role="button" aria-haspopup="true" aria-expanded="false">
                  @if (isset(Auth::user()->mmc))
                      <span class="mmc-badge-text">{{ Auth::user()->mmc->name }}</span>
                  @endif
                  {{ Auth::user()->name }}&nbsp;<span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                @if (Auth::user()->hasRole('administrator'))
                    <li><a href="/users">Пользователи</a></li>
                    <li><a href="/services">Услуги</a></li>
                    <li @if ($active == 'companies') class="active" @endif><a href="/admin/companies">Операторы</a></li>
                    <li @if ($active == 'blanks') class="active" @endif><a href="/blanks">Бланки</a></li>
                    <li role="separator" class="divider"></li>
                @endif

                @if (Auth::user()->hasRole('business.manager'))
                    <li><a href="/users">Пользователи</a></li>
                    <li role="separator" class="divider"></li>
                @endif
                <li><a href="/logout">Выход</a></li>
              </ul>
            </li>
            </ul>
        </div>
    </div>
</nav>
@endif
