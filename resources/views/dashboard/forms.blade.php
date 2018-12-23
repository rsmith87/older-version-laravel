@extends('adminlte::page')

@section('content')
    <div class="container dashboard cases col-sm-12 offset-sm-2">
        @include('dashboard.type_navigation.forms')
        @include('dashboard.includes.alerts')

        <div class="panel-documents">

        <h1 class="pull-left ml-4 mt-4 mb-3">
            <i class="fas fa-file-contract"></i> Forms
        </h1>
        @include('dashboard.includes.alerts')
    </div>
    <div>
    </div>

    @include('dashboard.includes.formbuilder-modal')

    @if (count($forms) === 0)
        <div class="alert alert-warning alert-dismissible fade in" role="alert">
            No forms for this user, yet! <strong>Create a new form!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (count($forms) > 0)
        <table id="user-forms" class="table dataTable table-responsive table-resposive table-striped table-hover">
            <thead>
                <tr>
                    <th class="sorting bg-primary" scope="col">uuid</th>
                    <th class="sorting bg-primary" scope="col">Name</th>
                    <th class="sorting bg-primary" scope="col">firm_share</th>
                    <th class="sorting bg-primary" scope="col">case_id</th>
                    <th class="sorting bg-primary" scope="col">firm_id</th>
                    <th class="sorting bg-primary" scope="col">created</th>
                    <th class="sorting bg-primary" scope="col">updated</th>
                </tr>
            </thead>
            <tbody>
            @foreach($forms as $form)
                <tr>
                    <td>{{ $form->uuid }}</td>
                    <td>{{ $form->name }}</td>
                    <td>{{ $form->firm_share }}</td>
                    <td>{{ $form->case_id }}</td>
                    <td>{{ $form->firm_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($form->created_at)->format('m/d/Y g:iA') }}</td>
                    <td>{{ \Carbon\Carbon::parse($form->updated_at)->format('m/d/Y g:iA') }}</td>
                </tr>
            @endforeach
    @endif

@endsection