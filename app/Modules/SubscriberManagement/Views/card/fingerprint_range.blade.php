@extends('master')

@section('css')
{{-- Validation --}}
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
{{-- Datetime Picker --}}
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
{{-- Datetime Picker --}}
<script src="{{asset('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
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
            datepicker_start_time: {required: true},
            datepicker_end_time: {required: true},
            product: {required: true}
            
        },
        messages: {
            from_card_number: {required: "Please enter from card number"},
            to_card_number: {required: "Please enter to card number"},
            datepicker_start_time: {required: "Please enter start date"},
            datepicker_end_time: {required: "Please enter end date"},
            product: {required: "Please select a product"}
            
        }
    });     

    // Distributors
    $('#users_id').select2({
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

    //Date picker Start Time
    $('#datepicker_start_time').datetimepicker({
        format: 'DD/MM/YYYY h:mm A'
    });

     //Date picker End Time
    $('#datepicker_end_time').datetimepicker({
        format: 'DD/MM/YYYY h:mm A'
    });
    


});



</script>

@endsection

@section('side_menu')

@endsection

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Card</h1>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Fingerprint Range</h3>
                </div>
                <!-- /.box-header -->
                <!-- form starts here -->
                {!! Form::open(array('url' => 'fingerprint_range_process', 'id' => 'fingerprint_range_form', 'class' => 'form-horizontal')) !!}
                <div class="box-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>From (Card Number)</label>
                            <input type="number" class="form-control pull-right" name="from_card_number" id="from_card_number">
                        </div>
                        <div class="form-group">
                            <label>To (Card Number)</label>
                            <input type="number" class="form-control pull-right" name="to_card_number" id="to_card_number">
                        </div>
                        <div class="form-group">
                            <label>Start Time</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" name="datepicker_start_time"  id="datepicker_start_time">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group">
                            <label>End Time</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" name="datepicker_end_time" id="datepicker_end_time">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group">
                            <label>Products</label>
                            <select class="form-control" name="product" id="product">
                                @foreach($products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                @endforeach
                              
                            </select>
                        </div>
                    </div>
                    <!-- /.col-md-4-->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
                <!-- /.box-footer -->
                {!! Form::close() !!}
                <!-- /.form ends here -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col-md-12-->
    </div>
    <!-- /.row-->
</section>
<!-- /.content -->

@endsection

