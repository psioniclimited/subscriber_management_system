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
        position: 'left'  // display the tips to the right of the element
    });

    // initialize validate plugin on the form
    $('#add_card_form').validate({
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
            card_id: {required: true},
            distributors_id: {required: true}
        },
        messages: {
            card_id: {required: "Please enter card id"},
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
    
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Add New Card</h3>
                </div>
                <!-- /.box-header -->
                <!-- form starts here -->
                {!! Form::open(array('url' => 'add_card_process', 'id' => 'add_card_form')) !!}

                <div class="box-body">
                    <div class="form-group">
                        <label>Card ID*</label>
                        <input type="number" class="form-control" name="card_id" id="card_id" placeholder="Enter card id">
                    </div>
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
        </div><!-- col-xs-6 -->
    </div><!-- row -->
</section><!-- /.content -->

@endsection

