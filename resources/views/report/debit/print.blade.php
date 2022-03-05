@extends('layouts.print_report')

@section('content')
    <style>
        * {
            font-size: 12px;
        }
        .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border: 1px solid #000 !important;
        }
        @media print {
            .btn-danger {
                display: none;
            }
        }
    </style>

    <div class="row mt20">
        <div class="col-md-12">
             <a href="/operator/report/debit?daterange={{ Request::get('daterange') }}&manager={{ Request::get('manager') }}&mmc={{ Request::get('mmc') }}&cashier={{ Request::get('cashier') }}&client={{ Request::get('client') }}" class="btn btn-danger pull-right">Закрыть</a>
        </div>
    </div>

    @include('report.debit.table', ['print' => true])

    <script>
        window.print();
    </script>
@endsection
