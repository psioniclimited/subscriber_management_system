@extends('master')

@section('css')

{{-- Validation --}}
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">

@endsection

@section('scripts')

{{-- Validation --}}
<script src="{{asset('plugins/validation/dist/jquery.validate.js')}}"></script>
<script src="{{asset('plugins/tooltipster/tooltipster.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/select2.full.js')}}"></script>
{{-- Page script --}}
<script>
$(document).ready(function () {
    // initialize tooltipster on form input elements
    $('form input, select').tooltipster({// <-  USE THE PROPER SELECTOR FOR YOUR INPUTs
        trigger: 'custom', // default is 'hover' which is no good here
        onlyOne: false, // allow multiple tips to be open at a time
        position: 'right'  // display the tips to the right of the element
    });

    // initialize validate plugin on the form
    $('#revoke_distributor_form').validate({
        errorPlacement: function (error, element) {

            var lastError = $(element).data('lastError'),
                    newError = $(error).text();

            $(element).data('lastError', newError);

            if (newError !== '' && newError !== lastError) {
                $(element).tooltipster('content', newError);
                $(element).tooltipster('show');
            }
        },
        success: function (label, element) {
            $(element).tooltipster('hide');
        },
        rules: {
            users_id: {required: true}
        },
        messages: {
            users_id: {required: "Please select a distributor"}
        }
    });

    // Distributors
    var distributor = $('#users_id');
    distributor.select2({
        placeholder: "Select a distributor",
        allowClear: true,
        ajax: {
            dataType: 'json',
            url: "{{URL::to('/')}}/select/distributors",
            delay: 250,
            data: function(params) {
                return {
                    term: params.term,
                    page: params.page
                }
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        } 
    });

    distributor.change(function(){
        $(this).valid(); // trigger validation on this element
    });
});
</script>

@endsection

@section('side_menu')

@endsection

@section('content')

<!-- Content header -->
<section class="content-header">
    
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-6">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Revoke Distributor</h3>
                </div><!-- /.box-header -->
                <!-- form starts here -->
                {!! Form::open(array('url' => 'revoke_distributor_process', 'id' => 'revoke_distributor_form')) !!}
                <div class="box-body">
                    <div class="form-group">
                        <label>Distributor</label>
                        <select class="form-control select2" name="users_id" id="users_id"></select>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-danger pull-right">Revoke all cards</button>
                </div><!-- /.box-footer -->
                {!! Form::close() !!}
                <!-- /.form ends here -->
            </div><!-- /.box -->
        </div><!-- col-xs-6 -->
    </div><!-- row -->
</section><!-- /.content -->

@endsection

