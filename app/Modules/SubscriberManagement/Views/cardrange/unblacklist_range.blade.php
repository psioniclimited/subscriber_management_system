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
    $('#unblacklist_range_form').validate({
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
            to_card_number: {required: true}
        },
        messages: {
            from_card_number: {required: "Please enter from card number"},
            to_card_number: {required: "Please enter to card number"}
        }
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
                    <h3 class="box-title">Unblacklist Card Range</h3>
                </div><!-- /.box-header -->
                <!-- form starts here -->
                {!! Form::open(array('url' => 'unblacklist_range_process', 'id' => 'unblacklist_range_form')) !!}
                <div class="box-body">
                    <div class="form-group">
                        <label>From (Card Number)</label>
                        <input type="number" class="form-control pull-right" name="from_card_number" id="from_card_number">
                    </div>
                    <div class="form-group">
                        <label>To (Card Number)</label>
                        <input type="number" class="form-control pull-right" name="to_card_number" id="to_card_number">
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

