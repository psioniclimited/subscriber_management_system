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
    $('#entitle_card_range_form').validate({
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
            datepicker_start_date: {required: true},
            timepicker_start_time: {required: true},
            datepicker_end_date: {required: true},
            timepicker_end_time: {required: true},
            product: {required: true}
            
        },
        messages: {
            from_card_number: {required: "Please enter from card number"},
            to_card_number: {required: "Please enter to card number"},
            datepicker_start_date: {required: "Please select start date"},
            timepicker_start_time: {required: "Please select start time"},
            datepicker_end_date: {required: "Please select end date"},
            timepicker_end_time: {required: "Please select end time"},
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

    var fullDate = new Date()
    console.log(fullDate);
    //Thu Otc 15 2014 17:25:38 GMT+1000 {}
      
    //convert month to 2 digits
    var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) :(fullDate.getMonth()+1);
    console.log(currentDate);
    var currentDate = fullDate.getDate() + "/" + twoDigitMonth + "/" + fullDate.getFullYear();
    console.log(currentDate);
    //15/10/2014

    //Date picker start date
    $('#datepicker_start_date').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        startDate: "today"
    });

    //Timepicker start time
    $("#timepicker_start_time").timepicker({
      showInputs: false
    });

    //Date picker end date
    $('#datepicker_end_date').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        startDate: "today"
    });

    //Timepicker end time
    $("#timepicker_end_time").timepicker({
      showInputs: false
    });

    function disable_product(disable_value) {
        $("#datepicker_start_date").prop('disabled', disable_value);
        $("#timepicker_start_time").prop('disabled', disable_value);
        $("#datepicker_end_date").prop('disabled', disable_value);
        $("#timepicker_end_time").prop('disabled', disable_value);
    }
    let initial_days = '{{ $products[0]->days }}';
    if (initial_days != 0) {
        disable_product(true);
    }
    $('.product_entitle').change(function(event) {
        let days = $('select[name="product"] :selected').attr('class');
        if (days != 0) {
            disable_product(true);
        }
        else {
            disable_product(false);
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
                    <h3 class="box-title">Entitle Card Range</h3>
                </div>
                <!-- /.box-header -->
                <!-- form starts here -->
                {!! Form::open(array('url' => 'entitle_card_range_process', 'id' => 'entitle_card_range_form')) !!}
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
                        <label>Products</label>
                        <select class="form-control product_entitle" name="product" id="product">
                            @foreach($products as $product)
                                @if($product->days === 0)
                                    <option value="{{$product->id}}" class="{{$product->days}}">{{$product->name}}</option>
                                @else
                                    <option value="{{$product->id}}" class="{{$product->days}}">{{$product->name . ' ' . $product->days}} days</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Start Date</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" name="datepicker_start_date" id="datepicker_start_date">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div><!-- col -->
                        
                        <div class="col-xs-6">
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Start Time</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control timepicker" name="timepicker_start_time" id="timepicker_start_time">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div><!-- timepicker -->
                        </div><!-- col -->
                    </div><!-- row -->
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>End Date</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" name="datepicker_end_date" id="datepicker_end_date">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div><!-- col -->
                        
                        <div class="col-xs-6">
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>End Time</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control timepicker" name="timepicker_end_time" id="timepicker_end_time">
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

