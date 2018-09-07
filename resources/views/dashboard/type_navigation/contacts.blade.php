<nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-sm btn-info" data-toggle="modal" data-target="#{{ Request::segment(2) === 'clients' ? str_replace('s', '', Request::segment(2)) : Request::segment(2) }}-modal" href="#"><i class="fa fa-plus"></i> <i class="fa fa-user"></i> Add {{ str_replace('s', '', Request::segment(2)) }}</a>
    @hasanyrole('firm_manager|administrator')
        <a class="nav-item nav-link btn btn-sm btn-info"  href="/dashboard/{{ Request::segment(2) }}/firm"><i class="fa fa-users"></i> Firm {{ Request::segment(2) }}</a>
    @endhasrole
</nav>