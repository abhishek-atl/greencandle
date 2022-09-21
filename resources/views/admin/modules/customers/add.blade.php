@extends('admin.layouts.master')

@section('title') @if($isEdit) Edit @else Add @endif Customer @endsection

@section('content')
<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.customers') }}">Customers</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">@if($isEdit) Edit @else Add @endif</a></li>
        </ol>
    </div>
</div>
<div class="container-fluid">
    <div class="card">
        <div class="card-header mt-3">
            <h4 class="ml-2">@if($isEdit) Edit @else Add @endif Customer</h4>
        </div>
        <div class="card-body">
            <div class="form-validation">
                <form id="storeCustomerForm" action="{{ route('admin.customers.store') }}" method="post">
                    @csrf
                    @if($isEdit)
                    <input type="hidden" name="id" value="{{ $customer->id }}">
                    @endif
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label" for="customer_code">Customer Code <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="customer_code" name="customer_code" placeholder="Enter customer_code..." value="@if($isEdit){{ $customer->customer_code ?? '' }}@endif">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Group<span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <select class="form-control" name="group_id">
                                <option selected>Select Group</option>
                                @foreach($groups as $group)
                                <option value="{{ $group->id }}" @if($isEdit && $customer->group_id == $group->id) selected @endif>{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Active <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <label class="radio-inline mr-3">
                                <input type="radio" name="active" id="active_yes" value="1" @if($isEdit && $customer->active) checked @endif> Yes</label>
                            <label class="radio-inline mr-3">
                                <input type="radio" name="active" id="active_no" value="0" @if($isEdit && !$customer->active) checked @endif> No</label>
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
{!! JsValidator::formRequest('App\Http\Requests\StoreCustomerRequest', '#storeCustomerForm') !!}
<script>
    $(document).ready(function() {

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