<nav class="nav nav-pills">
    <a class="nav-item nav-link btn btn-sm btn-info" href="/dashboard/{{ Request::segment(3) }}s"><i
                class="fas fa-arrow-left"></i> Back to {{ Request::segment(3) }}s</a>
    <a class="nav-item nav-link btn btn-sm btn-info" data-toggle="modal" data-target="#contacts-modal" href="#"><i
                class="fa fa-user"></i> Edit {{ Request::segment(3) }}</a>
    <a class="nav-item nav-link btn btn-sm btn-info" href="#"><i class="fas fa-print"></i> Print {{ Request::segment(3) }}
    </a>
    <a class="nav-item nav-link btn btn-sm btn-info" data-toggle="modal" data-target="#add-notes-modal" href="#"><i
                class="fas fa-sticky-note"></i> Add note</a>
    <a class="nav-item nav-link btn btn-sm btn-info" data-toggle="modal" data-target="#add-communication-modal" href="#"><i
                class="fas fa-comments"></i> Log communication</a>
    @if(count($case) > 0)
        <a class="nav-item nav-link btn btn-sm btn-info" href="/dashboard/cases/case/{{ $case->case_uuid }}"><i
                    class="fa fa-gavel"></i> View case</a>
    @endif
    @if(empty($contact->case_id) || $contact->case_id === 0)
        <a class="nav-item nav-link btn btn-sm btn-info" data-toggle="modal" data-target="#relate-client-to-case" href="#"><i
                    class="fa fa-user"></i> <i class="fa fa-plus"></i> <i class="fa fa-gavel"></i>
            Relate {{ Request::segment(3) }} to case</a>
    @endif
    <a class="nav-item nav-link btn btn-sm btn-danger" data-toggle="modal" data-target="#delete-modal" href="#"><i
                class="fas fa-trash-alt"></i> Delete {{ Request::segment(3) }}</a>
</nav>