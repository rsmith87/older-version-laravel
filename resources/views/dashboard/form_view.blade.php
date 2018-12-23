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

        <iframe id="form-builder" src="/dashboard/forms/form_builder/{{ $form->uuid }}"></iframe>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-render.min.js"></script>

    <script type="text/javascript">
        jQuery($ => {
            var uuid = "{!! $form->uuid !!}";
            var data = {!! $form->data !!};
            //$formContainer = $(document.getElementById('fb-rendered-form')),
            var $fbEditor = $(document.getElementById('fb-editor')),
                $formContainer = $(document.getElementById('fb-rendered-form')),
                fbOptions = {
                    onSave: function() {
                        $fbEditor.toggle();
                        $formContainer.toggle();
                        $('form', $formContainer).formRender({
                            formData: formBuilder.formData
                        });
                    }
                },
                formBuilder = $fbEditor.formBuilder(fbOptions);

            $('.edit-form', $formContainer).click(function() {
                $fbEditor.toggle();
                $formContainer.toggle();
            });



            $('#submit-form').click(function(){
                console.log(JSON.stringify($renderer.userData));
                $.ajax(
                    {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        datatype: 'json',
                        url: '/dashboard/forms/user/post',
                        data: {
                            'user_data': JSON.stringify($renderer.userData),
                            'form_uuid': uuid,
                        },
                        success: function (data) {
                            console.log('success');

                        },
                    });
            });

        });

    </script>
@endsection