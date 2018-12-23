

<nav class="nav nav-pills">
    @if(Request::segment(3) == 'view' || Request::segment(3) == 'edit')
        <a class="nav-item nav-link btn btn-sm btn-info" href="/dashboard/forms"><i class="fas fa-fast-backward"></i> Back to forms</a>
        @if(Request::segment(5) != 'results' && Request::segment(6) == "")
            <a class="nav-item nav-link btn btn-sm btn-info" href="/dashboard/forms/view/{{ $form->uuid }}/results"><i class="fas fa-file-contract"></i> Results</a>
        @endif
    @else
        <a class="nav-item nav-link btn btn-sm btn-info" data-toggle="modal" data-target="#formbuilder-modal" href="#"><i class="fas fa-file-contract"></i> Add form</a>
    @endif
</nav>