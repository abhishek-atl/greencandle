@extends('admin.layouts.master')

@section('title') Reports @endsection

@section('content')
<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.reports') }}">Report</a></li>
        </ol>
    </div>
</div>
<div class="container-fluid v-cloak">
    <div class="card">
        <div class="card-header mt-3 row justify-content-between">
            <h4 class="ml-4">Report</h4>
        </div>
        <form method="get">
            <div class="d-flex justify-content-end mr-3">
                <input class="form-control input-rounded w-25" type="text" placeholder="Search.." id="date" name="date" value="{{ $defaultDate }}">
                <input type="submit" class="btn mb-1 btn-rounded btn-outline-primary btn-sm ml-3" value="Search">
            </div>
        </form>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Total Amount</th>
                            <th>Total Brokerage</th>
                            <th>Grand Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                        <tr>
                            <td>{{ $loop->index }}</td>
                            <td>{{ $record->name}}</td>
                            <td>{{ $record->total_amount}}</td>
                            <td>{{ $record->total_brokerage}}</td>
                            <td>{{ $record->grand_total}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection

@push('pageScripts')
<script>
    let options = {
        format: "dd/mm/yyyy",
        calendarWeeks: true,
        autoclose: true,
        daysOfWeekHighlighted: "1,6",
    }
    $(document).ready(function() {
        @if(Session::has('success_msg'))
        toastr.success("{{ Session::get('success_msg') }}")
        @endif
        $('#date').datepicker(options);
    })
</script>
@endpush