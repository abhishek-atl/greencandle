@extends('admin.layouts.master')

@section('title') Customers @endsection

@section('content')
<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.customers') }}">Customers</a></li>
        </ol>
    </div>
</div>
<div class="container-fluid v-cloak">
    <div class="card">
        <div class="card-header mt-3 row justify-content-between">
            <h4 class="ml-4">Customers</h4>
            <a href="{{ route('admin.customers.add') }}" class="btn mr-3 btn-outline-primary">Add Customer</a>
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
                            <th>Customer Code</th>
                            <th>Group</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in items" :key="item.key">
                            <td>@{{ item.id }}</td>
                            <td>@{{ item.customer_code}}</td>
                            <td>@{{ item.group.name}}</td>
                            <td v-if="item.active">Yes</td>
                            <td v-else>No</td>
                            <td>
                                <a :href="'{{ route("admin.customers.edit") }}/' + item.id" type="button" class="btn mb-1 btn-rounded btn-outline-primary btn-sm"><i class="fa fa-pencil"></i></a>
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
    let indexRoute = 'admin.customers.indexJson';
    $(document).ready(function() {
        @if(Session::has('success_msg'))
        toastr.success("{{ Session::get('success_msg') }}")
        @endif
    })
</script>
<script src="{{ asset('backend/js/modules/list.js') }}"></script>
@endpush