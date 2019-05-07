@extends('master')

@section('css')

{{-- Validation --}}
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
{{-- Datetime picker --}}
<link rel="stylesheet" href="{{asset('plugins/datetimepicker/css/bootstrap-datetimepicker.min.css')}}">

@endsection

@section('scripts')

{{-- Validation --}}
<script src="{{asset('plugins/validation/dist/jquery.validate.js')}}"></script>
<script src="{{asset('plugins/tooltipster/tooltipster.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/select2.full.js')}}"></script>
{{-- Moment --}}
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
{{-- Datetime picker --}}
<script src="{{asset('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
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
    $('#create_card_range_form').validate({
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
        error: function (label, element) {
            $(element).tooltipster('hide');
        },
        rules: {
            from_card_number: {required: true},
            to_card_number: {required: true},
            distributors_id: {required: true}
        },
        messages: {
            from_card_number: {required: "Please enter from card number"},
            to_card_number: {required: "Please enter to card number"},
            distributors_id: {required: "Please select a distributor"}
        }
    });     



    var distributor = $('#distributors_id'),
        sub_distributor = $('#sub_distributors_id');

    var init_select = function(parameters)  {
        var custom_url = "{{URL::to('/')}}/";
        var placeholder_text = 'Enter ';
        parameters.selector_id.select2({
            allowClear: true,
            placeholder: placeholder_text + parameters.placeholder,
            ajax: {
                dataType: 'json',
                url: custom_url + parameters.url,
                delay: 250,
                data: function(params) {
                    return {
                            term: params.term,
                            distributor_id: parameters.value_id.val(),
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
        // Pass it as a parameter to init_select
        // Initialize select2 on sector
    }

    var parameters = {
        placeholder: "Distributor",
        url: 'select/distributors',
        selector_id: distributor,
        value_id: distributor
    }

    init_select(parameters);
    // $(this).valid();

    //on distributor change initialize sub_distributor
    distributor.change(function(){
        console.log("Distributor Change");
        //clear selected value of distributor
        sub_distributor.val(null).trigger("change");
        // Set the parameters as an object
        var parameters = {
            placeholder: "Sub Distributor",
            url: 'select/subdistributors',
            selector_id: sub_distributor,
            value_id: distributor
        }
        // Pass it as a parameter to init_select
        // Initialize select2 on distributor
        init_select(parameters);
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
    <h1>Card</h1>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Create Card Range</h3>
                </div><!-- /.box-header -->
                <!-- form starts here -->
                {!! Form::open(array('url' => 'create_card_range_process', 'id' => 'create_card_range_form')) !!}
                <div class="box-body">
                    <div class="form-group">
                        <label>From (Card Number)</label>
                        <input type="number" class="form-control pull-right" name="from_card_number" id="from_card_number">
                    </div>
                    <div class="form-group">
                        <label>To (Card Number)</label>
                        <input type="number" class="form-control pull-right" name="to_card_number" id="to_card_number">
                    </div>
                    <!-- <div class="form-group">
                        <label>Distributor</label>
                        <select class="form-control" name="users_id" id="users_id"></select>
                    </div> -->
                    <div class="form-group">
                        <label>Distributor</label>
                        <select class="form-control select2" name="distributors_id" id="distributors_id"></select>
                    </div>
                    <div class="form-group">
                        <label>Sub Distributor</label>
                        <select class="form-control select2" name="sub_distributors_id" id="sub_distributors_id"></select>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div><!-- /.box-footer -->
                {!! Form::close() !!}
                <!-- /.form ends here -->
            </div><!-- /.box -->
        </div><!-- /.col-xs-6-->
    </div><!-- /.row-->
</section><!-- /.content -->

@endsection

