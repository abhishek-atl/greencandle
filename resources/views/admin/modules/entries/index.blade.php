@extends('admin.layouts.master')

@section('title') Entries @endsection

@section('content')
<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.entries') }}">Entries</a></li>
        </ol>
    </div>
</div>
<div class="container-fluid">
    <div class="card">
        <div class="card-header mt-3 row justify-content-between">
            <h4 class="ml-4">Entries</h4>
            <a href="{{ route('admin.entries.add') }}" class="btn mr-3 btn-outline-primary">Add Entry</a>
        </div>
        <div class="d-flex justify-content-end mr-3">
            <input class="form-control input-rounded w-25" type="text" placeholder="Search.." v-model="search_text" @input="search">
            <button class="btn mb-1 btn-rounded btn-outline-primary btn-sm ml-3" v-if="search_text" @click="clearSearch">Clear</button>
        </div>
        <div class="card-body">
            <div class="table-responsive" v-if="items && items.length > 0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Brokerage</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in items" :key="index">
                            <td>@{{ index + from }}</td>
                            <td>@{{ item.start_date}}</td>
                            <td>@{{ item.end_date}}</td>
                            <td>@{{ item.customer.customer_code}}</td>
                            <td>@{{ item.amount}}</td>
                            <td>@{{ item.brokerage}}</td>
                            <td v-if="item.active">Yes</td>
                            <td v-else>No</td>
                            <td>
                                <a :href="'{{ route("admin.entries.edit") }}/' + item.id" type="button" class="btn mb-1 btn-rounded btn-outline-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                <a href="javascript:void(0)" type="button" class="btn mb-1 ml-3 btn-rounded btn-outline-danger btn-sm" @click="deleteConfirm(item.id)">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>

                @include('admin.common.pagination')

            </div>
            <div v-else>
                <x-alert type="warning" message="No records to show!" />
            </div>
        </div>
    </div>
</div>
@endsection

@push('pageScripts')
<script>
    let indexRoute = 'admin.entries.indexJson';
    $(document).ready(function() {
        @if(Session::has('success_msg'))
        toastr.success("{{ Session::get('success_msg') }}")
        @endif
    })
</script>
<script src="{{ asset('backend/js/modules/list.js') }}"></script>
@endpush