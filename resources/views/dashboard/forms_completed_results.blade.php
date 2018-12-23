@extends('adminlte::page')

@section('content')
    <div class="container dashboard cases col-sm-12 offset-sm-2">
        @include('dashboard.type_navigation.forms')
        @include('dashboard.includes.alerts')

        <div class="panel-documents">

            <h1 class="pull-left ml-4 mt-4 mb-3">
                <i class="fas fa-file-contract"></i> Form Result
            </h1>
            @include('dashboard.includes.alerts')
        </div>
        <div class="clearfix"></div>

        @include('dashboard.includes.formbuilder-modal')

        @if (count($form) === 0)
            <div class="alert alert-warning alert-dismissible fade in" role="alert">
                No forms for this user, yet! <strong>Create a new form!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

    @if (count($form) > 0)
        @foreach($data as $d)
            <label>{{ $d->label }}</label>
            <p>{{ $d->userData[0] }}</p>
            @endforeach

    @endif


@endsection

