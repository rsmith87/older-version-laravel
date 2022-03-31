@extends('adminlte::page')

@section('content')

    <div class="panel-documents">

        <h1 class="pull-left ml-4 mt-4 mb-3">
            <i class="fas fa-compact-disc"></i> Documents
        </h1>
        @include('dashboard.includes.alerts')
        <div class="clearfix"></div>
        <div class="row documents">
            <div class="col-sm-3 directories">
                <ul>
                    <li>First Document</li>
                    <li>Documents 
                        <ul>
                            <li>Document 1</li>
                            <li>Document 2</li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="col-sm-9 documents">
                <div class="row">
                    <div class="col-sm-4">
                        <p>Docment name</p>
                    </div>
                    <div class="col-sm-3">
                        <p>Docment name</p>
                    </div>
                    <div class="col-sm-5">
                        <p>Docment name</p>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection