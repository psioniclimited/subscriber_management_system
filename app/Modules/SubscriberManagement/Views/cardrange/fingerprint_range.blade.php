@extends('master')

@section('css')

{{-- Validation --}}
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- Bootstrap date picker -->
<link rel="stylesheet" href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css')}}">
<!-- Bootstrap time picker -->
<link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">

@endsection

@section('scripts')

{{-- Validation --}}
<script src="{{asset('plugins/validation/dist/jquery.validate.js')}}"></script>
<script src="{{asset('plugins/tooltipster/tooltipster.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/select2.full.js')}}"></script>
{{-- Moment --}}
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- Bootstrap date picker -->
<script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<!-- Bootstrap time picker -->
<script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
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
    $('#fingerprint_range_form').validate({
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
            duration: {required: true},
            datepicker_expire_date: {required: true},
            timepicker_expire_time: {required: true}
        },
        messages: {
            from_card_number: {required: "Please enter from card number"},
            to_card_number: {required: "Please enter to card number"},
            duration: {required: "Please enter duration"},
            datepicker_expire_date: {required: "Please enter fingerprint expire date"},
            timepicker_expire_time: {required: "Please enter fingerprint expire time"}
        }
    });     

    //Date picker expire date
    $('#datepicker_expire_date').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        startDate: "today"
    });

    //Timepicker expire time
    $("#timepicker_expire_time").timepicker({
      showInputs: false
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
                    <h3 class="box-title">Fingerprint Range</h3>
                </div>
                <!-- /.box-header -->
                <!-- form starts here -->
                {!! Form::open(array('url' => 'fingerprint_range_process', 'id' => 'fingerprint_range_form')) !!}
                <div class="box-body">
                    <div class="form-group">
                        <label>From (Card Number)</label>
                        <input type="number" class="form-control pull-right" name="from_card_number" id="from_card_number">
                    </div>
                    <div class="form-group">
                        <label>To (Card Number)</label>
                        <input type="number" class="form-control pull-right" name="to_card_number" id="to_card_number">
                    </div>
                    <div class="form-group">
                        <label>Fingerprint Duration</label>
                        <input type="number" class="form-control" name="duration" id="duration" placeholder="Enter fingerprint duration"/>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Fingerprint Expire Date</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" name="datepicker_expire_date" id="datepicker_expire_date">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div><!-- col -->
                        
                        <div class="col-xs-6">
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Fingerprint Expire Time</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control timepicker" name="timepicker_expire_time" id="timepicker_expire_time">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div><!-- timepicker -->
                        </div><!-- col -->
                    </div><!-- row -->
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

