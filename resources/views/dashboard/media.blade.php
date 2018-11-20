@extends('adminlte::page')

@section('content')
    <script type="text/javascript">
        var $ = jQuery;
    </script>
    <script src="{{ asset('vendor/laravel-filemanager/js/lfm.js') }}"></script>


    <div class="panel-documents">

        <h1 class="pull-left ml-4 mt-4 mb-3">
            <i class="fas fa-compact-disc"></i> Media
        </h1>
        @include('dashboard.includes.alerts')
    </div>
    <div>


        <iframe src="/filemanager?type=file"
                style="width: 100%; height: 85vh; overflow: hidden; border: none;"></iframe>

        <script type="text/javascript">
            var $ = jQuery;
            $('#lfm').filemanager('image');

            $('#lfm').filemanager('file');
        </script>


@endsection