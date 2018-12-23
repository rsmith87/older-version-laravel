<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form-builder-overrides.css') }}">
</head>
<body class="form-builder-body">
<div id="form-errors"></div>
@if(count($form) > 0)
<input type="hidden" name="form_uuid" value="{{ $form['uuid'] }}" />
@endif
<label>Form name</label>
<input type="text" name="form_name" class="form-control mb20" id="iframe-form-name-input" value="{{ count($form) > 0 ? $form['name'] : "" }}" />
<div id="fb-editor"></div>
<div id="fb-rendered-form">
    <form action="#"></form>
    <button class="btn btn-warning edit-form">Edit form</button>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
<script src="https://formbuilder.online/assets/js/form-render.min.js"></script>
<script type="text/javascript">

    jQuery(function($) {
        var $fbEditor = $(document.getElementById('fb-editor')),
            $formContainer = $(document.getElementById('fb-rendered-form')),
            fbOptions = {
                actionButtons: [{
                    id: 'smile',
                    className: 'btn btn-success',
                    label: 'Preview',
                    type: 'button',
                    events: {
                        click: function() {
                            $fbEditor.toggle();
                            $formContainer.toggle();
                            $('form', $formContainer).formRender({
                                formData: formBuilder.formData
                            });
                        }
                    }
                }],
                onSave: function() {
                    $form_name = $('input[name="form_name"]').val();
                    $uuid = $('input[name="form_uuid"]').val();

                    if($form_name == "" || $uuid == "") {
                        $('#form-errors').html('<p class="text-danger">Error saving form.  Please refresh the page and try again.</p>');
                    } else {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax(
                            {
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: 'POST',
                                datatype: 'json',
                                url: '/dashboard/forms/post',
                                data: {
                                    'data': formBuilder.actions.getData('json'),
                                    'form_name': $form_name,
                                    'uuid': $uuid
                                },
                                success: function (data) {
                                    console.log('success');

                                },
                            });
                    }
                },
                disableFields: [
                    'autocomplete',
                    'file',
                    'paragraph',
                    'header'
                ],
                disabledActionButtons: ['data'],
                controlPosition: 'left',
                @if(count($form) > 0)
                    formData: {!! $form['data'] !!}
                @endif

            },


            formBuilder = $fbEditor.formBuilder(fbOptions);



        $('.edit-form', $formContainer).click(function() {
            $fbEditor.toggle();
            $formContainer.toggle();
        });
    });
</script>
</body>








</html>