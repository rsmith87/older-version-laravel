

<nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-sm btn-info" data-toggle="modal" data-target="#case-modal" href="#"><i class="fas fa-balance-scale"></i> Add case</a>
    @hasanyrole('firm_manager|administrator')
        <a class="nav-item nav-link btn btn-sm btn-info"  href="/dashboard/{{ Request::segment(2) }}/firm"><i class="fas fa-balance-scale"></i> Firm {{ Request::segment(2) }}</a>
    @endhasrole
</nav>