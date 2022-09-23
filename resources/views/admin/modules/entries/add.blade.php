@extends('admin.layouts.master')

@section('title') @if($isEdit) Edit @else Add @endif Entry @endsection

@section('content')
<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.entries') }}">Entries</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">@if($isEdit) Edit @else Add @endif</a></li>
        </ol>
    </div>
</div>
<div class="container-fluid">
    <div class="card">
        <div class="card-header mt-3">
            <h4 class="ml-2">@if($isEdit) Edit @else Add @endif Entry</h4>
        </div>
        <div class="card-body">
            <div class="form-validation">
                <form id="storeEntryForm" action="{{ route('admin.entries.store') }}" method="post">
                    @csrf
                    @if($isEdit)
                    <input type="hidden" name="id" value="{{ $entry->id }}">
                    @endif
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="start_date">Start Date<span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="start_date" name="start_date" value="@if($isEdit){{ Carbon\Carbon::createFromFormat('Y-m-d', $entry->start_date)->format('d/m/Y')  }}@endif">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="end_date">End Date<span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="end_date" name="end_date" value="@if($isEdit){{ Carbon\Carbon::createFromFormat('Y-m-d', $entry->end_date)->format('d/m/Y') }}@endif">

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="customer_id">Customer <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <select name="customer_id" class="form-control">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id}}" @if($isEdit && $customer->id == $entry->customer->id) selected @endif>{{ $customer->customer_code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="amount">Amount<span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="amount" name="amount" value="@if($isEdit){{ $entry->amount ?? '' }}@endif">

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="brokerage">Brokerage<span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="brokerage" name="brokerage" value="@if($isEdit){{ $entry->brokerage ?? '' }}@endif">
                        </div>
                    </div>

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
{!! JsValidator::formRequest('App\Http\Requests\StoreEntryRequest', '#storeEntryForm') !!}
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

        var summernoteEle = $(".summernote");
        summernoteEle.summernote({
            height: 150,
            minHeight: null,
            maxHeight: null,
            focus: !1,
            callbacks: {
                onChange: function(contents, $editable) {
                    summernoteEle.val(summernoteEle.summernote('isEmpty') ? "" : contents);
                    summernoteEle.valid()
                }
            }
        });
    });
</script>
@endpush