<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/users">MMC</a>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li @if ($active == 'users') class="active" @endif><a href="/users">Пользователи</a></li>
                <li @if ($active == 'services') class="active" @endif><a href="/services">Услуги</a></li>
                <li @if ($active == 'companies') class="active" @endif><a href="/admin/companies">Операторы</a></li>
                <li @if ($active == 'mmc') class="active" @endif><a href="/admin/mmc">Юниты</a></li>
                <li @if ($active == 'updates') class="active" @endif><a href="/admin/updates">Обновления</a></li>
                <li @if ($active == 'backup') class="active" @endif><a href="/admin/backup">Бекапы</a></li>
            </ul>

            <ul class="nav navbar-nav">
              <li class="dropdown dropdown-payqr-menu">
                <a href="#" class="dropdown-toggle dropdown-payqr" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    PayQR&nbsp;<span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li @if ($active == 'paymentreport') class="active" @endif><a href="/operator/report/payment">Статистика платежей</a></li>
                    <li @if ($active == 'taxreport') class="active" @endif><a href="/operator/report/tax">История платежей</a></li>
                    <li @if ($active == 'unrecognize') class="active" @endif><a href="/operator/report/tax/unrecognize">Нераспознанные платежи</a></li>
                    <li role="separator" class="divider"></li>
                    <li @if ($active == 'district') class="active" @endif><a href="/admin/districts">Районы и ОКТМО</a></li>
                    <li @if ($active == 'taxes') class="active" @endif><a href="/admin/taxes">Налоги</a></li>
                </ul>
              </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown dropdown-logout-menu">
                    <a href="#" class="dropdown-toggle dropdown-logout" role="button" aria-haspopup="true" aria-expanded="false">
                        Администратор
                        <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu">
                        <li><a href="/logout">Выход</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
