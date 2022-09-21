@extends('admin.layouts.master')

@section('title','Dashboard')

@section('content')
<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        </ol>
    </div>
</div>
<div class="container-fluid">
    <div class="card">
        <div class="card-header mt-3">
            <h4 class="ml-2">Dashboard</h4>
        </div>
        <div class="card-body">
            <div class="form-validation">
            </div>
        </div>
    </div>
</div>
@endsection