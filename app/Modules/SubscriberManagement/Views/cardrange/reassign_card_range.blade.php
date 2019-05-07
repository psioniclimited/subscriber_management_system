@extends('master')

@section('css')

{{-- Validation --}}
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
{{-- Datetime picker --}}
<link rel="stylesheet" href="{{asset('plugins/datetimepicker/css/bootstrap-datetimepicker.min.css')}}">

<!-- iCheck 1.0.1 -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">

@endsection

@section('scripts')

{{-- Validation --}}
<script src="{{asset('plugins/validation/dist/jquery.validate.js')}}"></script>
<script src="{{asset('plugins/tooltipster/tooltipster.js')}}"></script>
<!-- iCheck 1.0.1 -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
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
    $('#reassign_card_range_form').validate({
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
            from_card_number: {required: true},
            to_card_number: {required: true},
            distributors_id: {required: true}
        },
        messages: {
            from_card_number: {required: "Please enter from card number"},
            to_card_number: {required: "Please enter to card number"},
            distributors_id: {required: "Please enter to Distributor"},
        }
    });     

    var distributor = $('#distributors_id'),
    sub_distributor = $('#sub_distributors_id');
    /* get Distributors for Select2 */
    distributor.select2({
        placeholder: "Select a Distributor",
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

    /* get Sub Distributors for Select2 */
    sub_distributor.select2({
        placeholder: "Select a Sub Distributor",
        allowClear: true,
        ajax: {
            dataType: 'json',
            url: "{{URL::to('/')}}/select/subdistributors",
            delay: 250,
            data: function(params) {
                return {
                    term: params.term,
                    distributor_id: $('#distributors_id').val(),
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
    sub_distributor.change(function(){
        $(this).valid(); // trigger validation on this element
    });
    
    //on distributor change initialize sub_distributor
    distributor.change(function(){
        console.log("Distributor Change");
        //clear selected value of distributor
        sub_distributor.val(null).trigger("change");
        $(this).valid(); // trigger validation on this element 
    });


    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
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
                    <h3 class="box-title">Reassign Card Range</h3>
                </div><!-- /.box-header -->
                <!-- form starts here -->
                {!! Form::open(array('url' => 'reassign_card_range_process', 'id' => 'reassign_card_range_form')) !!}
                <div class="box-body">
                    <div class="form-group">
                        <label>From (Card Number)*</label>
                        <input type="number" class="form-control pull-right" name="from_card_number" id="from_card_number" min="0">
                    </div>
                    <div class="form-group">
                        <label>To (Card Number)*</label>
                        <input type="number" class="form-control pull-right" name="to_card_number" id="to_card_number" min="0">
                    </div>
                    @if(Entrust::hasRole('admin'))
                    <div class="form-group">
                        <label>Distributor</label>
                        <select class="form-control select2" name="distributors_id" id="distributors_id"></select>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>Sub Distributor</label>
                        <select class="form-control select2" name="sub_distributors_id" id="sub_distributors_id"></select>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="radio" name="checkbox_operation" class="flat-red" checked value="1">
                            Default
                        </label> &nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="radio" name="checkbox_operation" class="flat-red" value="2">
                            Remove customer from card
                        </label> &nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="radio" name="checkbox_operation" class="flat-red" value="3">
                            Transfer customer
                        </label>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div><!-- /.box-footer -->
                {!! Form::close() !!}
                <!-- /.form ends here -->
            </div><!-- /.box -->
        </div><!-- /.col-xs-6 -->
    </div><!-- /.row-->
</section><!-- /.content -->

@endsection