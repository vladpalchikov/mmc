<tr>
    <td>{{ $user->id }}</td>
    <td>{{ $user->login }}</td>
    <td>{{ $user->name }}</td>
    <td>
        @if ($user->getRoles()->count() > 0)
            {{ $user->getRoles()->first()->name }}
        @endif
    </td>
    <td>
      @if ($user->is_have_access_strict_report == 1)
        <strong>Да</strong>
      @else
        Нет
      @endif
    </td>
    <td>
      @if ($user->is_have_access_registry == 1)
        <strong>Да</strong>
      @else
        Нет
      @endif
    </td>
    <td>
        @if (isset($user->mmc_id) && $user->mmc_id != 0)
            {{ \MMC\Models\MMC::find($user->mmc_id)->name }}
        @endif
    </td>
    <td>{{ $user->phone }}</td>
    <td>
        <a href="/users/{{ $user->id }}/edit" class="btn btn-primary btn-sm pull-right">
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
        </a>
        <a href="/users/{{ $user->id }}/impersonate" class="btn btn-primary btn-sm pull-right mr10">
            <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
        </a>
    </td>
</tr>
