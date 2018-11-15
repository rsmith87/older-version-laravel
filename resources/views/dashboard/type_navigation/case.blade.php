<nav class="nav nav-pills">
    @if(Request::segment(3) === 'case')
        <a class="nav-item nav-link btn btn-sm btn-primary" href="/dashboard/cases/case/{{ $case->case_uuid }}"><i
                    class="fas fa-step-backward"></i> Back to case</a>
    @endif
    <a class="nav-item nav-link btn btn-sm btn-warning" data-toggle="modal" data-target="#case-edit-modal" href="#"><i
                class="fas fa-balance-scale"></i> Edit case</a>
    <a class="nav-item nav-link btn btn-sm btn-info" data-toggle="modal" data-target='#add-hours-modal' href='#'><i
                class="fas fa-clock"></i> Add hours worked</a>
    <a class="nav-item nav-link btn btn-sm btn-info" data-toggle="modal" data-target="#add-notes-modal" href="#"><i
                class="fas fa-sticky-note"></i> Add note</a>
    <a class="nav-item nav-link btn btn-sm btn-info" data-toggle="modal" data-target="#event-modal" href="#"><i
                class="fas fa-calendar-plus"></i> Add event</a>
    <a class="nav-item nav-link btn btn-sm btn-info" data-toggle="modal" data-target="#contacts-modal" href="#">
        <i class="fas fa-user"></i> Add Contact
    </a>
    <a class="nav-item nav-link btn btn-sm btn-info" data-toggle="modal" data-target="#tasklist-modal" href="#">
        <i class="fas fa-clipboard-list"></i> Add Taskboard
    </a>
    <!--<a class="nav-item nav-link btn btn-sm btn-info" data-toggle="modal" data-target="#document-modal" href="#">
      <i class="fas fa-file"></i> Add Document
    </a> |
    <!--<a class="nav-item nav-link btn btn-sm btn-info" data-toggle="modal" data-target="#document-modal" href="#"><i class="fas fa-cloud-upload-alt"></i> Add document</a>-->

    @if(count($order) > 0)
        <a class="nav-item nav-link btn btn-sm btn-info" href="/dashboard/invoices"><i class="fa fa-file"></i> View
            invoices</a>
    @endif

    @if(count($client) > 0)
        @if(count($firm_stripe) > 0)
            <a class="nav-item nav-link btn btn-sm btn-success" data-toggle="modal" data-target="#payment-modal-full" href="#"><i
                        class="fas fa-dollar-sign"></i> Bill client {{ $client->first_name }} {{ $client->last_name }}</a>
        @else
            <a class="nav-item nav-link btn btn-sm btn-success disabled" disabled="disabled" data-toggle="tooltip" data-html="true" data-placement="right" title="<em>You must complete your firm's signup for stripe. <a href='/dashboard/firm'>Click here</a>"><i
                        class="fas fa-dollar-sign"></i> Bill client {{ $client->first_name }} {{ $client->last_name }}</a>

        @endif
        <a class="nav-item nav-link btn btn-sm btn-success" data-toggle="modal" data-target="#change-client-modal" href="#">
            <i class="fas fa-user"></i> Change client
        </a>
    @else
        @if(count($clients) > 0)
            <a class="nav-item nav-link btn btn-sm btn-success" data-toggle="modal" data-target="#reference-modal-full" href="#">
                <i class="fas fa-dollar-sign"></i> Reference client to case</a>
        @endif
        <a class="nav-item nav-link btn btn-sm btn-success" data-toggle="modal" data-target="#client-modal" href="#">
            <i class="fas fa-user"></i> Create client for case
        </a>
    @endif
    <a class="nav-item nav-link btn btn-sm btn-primary" href="/dashboard/cases/case/{{ $case->case_uuid }}/timeline"><i
                class="fas fa-heartbeat"></i> View timeline</a>
    <!-- <a class="nav-item nav-link btn btn-sm btn-info" href="#"><i class="fas fa-user"></i> Case Progress</a> -->
    <a class="nav-item nav-link btn btn-sm btn-danger" data-toggle="modal" data-target="#delete-modal" href="#"><i
                class="fas fa-trash-alt"></i> Delete case</a>

</nav>