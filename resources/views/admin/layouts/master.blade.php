<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title') | {{ env('APP_NAME') }}</title>

    <!-- Custom Stylesheet -->
    <link href="{{ asset('backend/plugins/toastr/css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/plugins/sweetalert/css/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/plugins/summernote/dist/summernote.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    @stack('pageStyles')
</head>

<body>

    @include('admin.common.preloader')

    <!-- Main wrapper start -->
    <div id="app">

        @include('admin.common.header')

        @include('admin.common.sidebar')

        <!-- Content body start -->
        <div class="content-body">

            @yield('content')

        </div>
        <!-- Content body end -->

        @include('admin.common.footer')

    </div>
    <!-- Main wrapper end-->

    @routes()

    @include('admin.common.scripts')

    @stack('pageScripts')

</body>

</html>