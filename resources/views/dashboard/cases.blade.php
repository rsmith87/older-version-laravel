@extends('adminlte::page')

@section('content')

    <div class="container dashboard cases col-sm-12 offset-sm-2">
        @include('dashboard.type_navigation.cases')
        @include('dashboard.includes.alerts')

        <div>
            <div>
                <h1 class="pull-left ml-3 mt-4 mb-2">
                    <i class="fas fa-balance-scale"></i> Cases
                </h1>
                <div class="clearfix"></div>
                <p class="ml-3 mb-2">Cases shows all of your case information regarding all cases. Click on a case to
                    show information about that case.</p>


            </div>
            <div>
                @if (count($cases) === 0)
                    <div class="alert alert-warning alert-dismissible fade in" role="alert">
                        No cases for this user, yet! <strong>Add a new case above!</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (count($cases) > 0)
                    <table id="main"
                           class="table dataTable table-responsive table-resposive table-striped table-hover table-{{ $table_color }} table-{{ $table_size }}">
                        <thead>
                        <tr>
                            @foreach($columns as $column)
                                <th class="sorting bg-primary"
                                    scope="col">{{ ucfirst(str_replace("_", " ", $column)) }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cases as $case)
                            <tr>
                                @foreach ($columns as $column)
                                    @if ($column === 'statute_of_limitations' and $case->$column != "")
                                        <td>{{ \Carbon\Carbon::parse($case->column)->format('m/d/Y') }}</td>
                                    @else
                                        <td>{{ $case->$column }}</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>


    @include('dashboard.includes.case-modal')
@endsection