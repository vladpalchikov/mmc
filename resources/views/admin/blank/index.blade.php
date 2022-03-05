@extends('layouts.master')

@section('title', 'Бланки')

@section('menu')
    @include('partials.operator-menu', ['active' => 'blanks'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3>Бланки строгой отчетности</h3>
        </div>
    </div>

    <form action="{{ url('blanks') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row mt20">
            <div class="col-md-3">
                <label class="control-label text-light text-muted">Сторона А</label>
                <input type="file" name="side_a" class="form-control" accept="image/jpeg,image/x-png">
            </div>

            <div class="col-md-3">
                <label class="control-label text-light text-muted">Сторона Б</label>
                <input type="file" name="side_b" class="form-control" accept="image/jpeg,image/x-png">
            </div>

            <div class="col-md-3">
                <input type="submit" name="submit" class="btn btn-success mt25" value="Сохранить">
            </div>
        </div>
    </form>

    <div class="row mt30">
        <div class="col-md-12">
            <h5>История</h5>
        </div>
    </div>

    <div class="row mt30">
        <div class="col-md-5">
            <table class="table">
                @foreach ($blanks as $blank)
                    <tr>
                        <td>
                            {{ $blank->created_at->format('d.m.Y') }}
                        </td>
                        <td>
                            {{ $blank->user->name }}
                        </td>
                        <td>
                            <a data-fancybox="gallery" class="sm-img-wrap" href="/blanks/{{ $blank->id }}/file?file=side_a">Сторона А</a>
                        </td>
                        <td>
                            <a data-fancybox="gallery" class="sm-img-wrap" href="/blanks/{{ $blank->id }}/file?file=side_b">Сторона Б</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
