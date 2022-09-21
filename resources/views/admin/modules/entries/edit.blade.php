@extends('admin.layouts.master')

@section('title','Edit Entry')

@section('content')
<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.entries') }}">Entries</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit</a></li>
        </ol>
    </div>
</div>
<div class="container-fluid">
    <div class="card">
        <div class="card-header mt-3">
            <h4 class="ml-2">Edit Entry</h4>
        </div>
        <div class="card-body">
            <div class="form-validation">
                <form id="storeCustomerForm" action="{{ route('admin.entries.store') }}" method="post">
                    @csrf

                    <div class="form-group row">
                        <div class="col-lg-6"><input type="text" class="form-control" id="start_date" name="start_date" value="{{ Carbon\Carbon::createFromFormat('Y-m-d',$selectedEntry->start_date)->format('d/m/Y') }}" placeholder="Start Date"></div>
                        <div class="col-lg-6"><input type="text" class="form-control" id="end_date" name="end_date" value="{{ Carbon\Carbon::createFromFormat('Y-m-d',$selectedEntry->end_date)->format('d/m/Y') }}" placeholder="End Date"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">Customer</div>
                        <div class="col-md-4">Amount</div>
                        <div class="col-md-4">Brokerage</div>
                    </div>

                    @foreach($entries as $entry)

                    <div class="form-group row">
                        <input type="hidden" name="id[{{ $entry->customer->id }}]" value="{{ $entry->id}}" />
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="customer[{{ $entry->customer->id }}]" value="{{ $entry->customer->customer_code }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="amount[{{ $entry->customer->id }}]" value="{{ $entry->amount }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="brokerage[{{ $entry->customer->id }}]" value="{{ $entry->brokerage }}">
                        </div>
                    </div>
                    @endforeach

                    <div class="form-group row">
                        <div class="col-lg-8 ml-auto">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('pageScripts')
<script>
    $(document).ready(function() {
        let options = {
            format: "dd/mm/yyyy",
            calendarWeeks: true,
            autoclose: true,
            daysOfWeekHighlighted: "1,6",
        }
        $('#start_date').datepicker(options);
        $('#end_date').datepicker(options);
    });
</script>

@endpush