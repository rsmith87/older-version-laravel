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

        @if (count($form) === 0)
            <div class="alert alert-warning alert-dismissible fade in" role="alert">
                No forms for this user, yet! <strong>Create a new form!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (count($form) > 0)
            <table id="user-forms-completed" class="table dataTable table-responsive table-resposive table-striped table-hover">
                <thead>
                <tr>
                    <th class="sorting bg-primary" scope="col">uuid</th>
                    <th class="sorting bg-primary" scope="col">Form UUID</th>
                    <th class="sorting bg-primary" scope="col">Name</th>
                    <th class="sorting bg-primary" scope="col">created</th>
                    <th class="sorting bg-primary" scope="col">updated</th>
                </tr>
                </thead>
                <tbody>
                @foreach($form->Completed as $f)
                    <tr>
                        <td>{{ $f->uuid }}</td>
                        <td id="f-uuid">{{ $f->form_uuid }}</td>
                        <td>{{ $f->user_data }}</td>
                        <td>{{ \Carbon\Carbon::parse($f->created_at)->format('m/d/Y g:iA') }}</td>
                        <td>{{ \Carbon\Carbon::parse($f->updated_at)->format('m/d/Y g:iA') }}</td>
                    </tr>
                @endforeach
                @endif
                </tbody>
            </table>

            @foreach($form->Completed as $f)
                <div class="modal fade" id="view-form-results-{{ $f->uuid }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h3>
                                    <i class="fas fa-sticky-note"></i> Form results
                                </h3>
                                <div class="clearfix"></div>
                                <hr/>
                                <div class="form-results">
                                    {{ $f->user_data }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


@endsection

