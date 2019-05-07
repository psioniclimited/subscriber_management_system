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
<!-- bootstrap slider -->
<link rel="stylesheet" href="{{asset('plugins/bootstrap-slider/slider.css')}}">
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
<!-- Bootstrap slider -->
<script src="{{asset('plugins/bootstrap-slider/bootstrap-slider.js')}}"></script>

<script>
    $(document).ready(function () {

        // initialize tooltipster on form input elements
        $('form textarea, input, select').tooltipster({// <-  USE THE PROPER SELECTOR FOR YOUR INPUTs
            trigger: 'custom', // default is 'hover' which is no good here
            onlyOne: false, // allow multiple tips to be open at a time
            position: 'left'  // display the tips to the right of the element
        });

        // initialize validate plugin on the form
        $('#send_osd_form').validate({
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
                cards_id: {required: true},
                text: {required: true},
                datepicker_expire_date: {required: true},
                timepicker_expire_time: {required: true},
                show_time_length: {required: true},
                show_times: {required: true}
            },
            messages: {
                cards_id: {required: "Please select a card"},
                text: {required: "Please enter text"},
                datepicker_expire_date: {required: "Please enter expire date"},
                timepicker_expire_time: {required: "Please enter expire time"},
                show_time_length: {required: "Please show time length"},
                show_times: {required: "Please show times"}
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

        // Card ID
        $('#cards_id').select2({
            placeholder: "Select a card_id",
            allowClear: true,
            ajax: {
                dataType: 'json',
                url: "{{URL::to('/')}}/auto/allcards",
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

        $('#cards_id').change(function(){
            $(this).valid(); // trigger validation on this element
        });

        // Coverage Rate
        $("#coverage_rate").slider();
        $("#coverage_rate").on("slide", function(coverageRate) {
            $("#coverage_rate_value").text(coverageRate.value);
        });

    });
</script>

@endsection


@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>OSD</h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Send OSD</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(array('url' => 'send_osd_process', 'id' => 'send_osd_form')) !!}
                    <div class="box-body">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>To (Card ID)</label>
                                <select class="form-control select2" name="cards_id" id="cards_id" style="width: 100%;">
                                        
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea class="form-control" rows="3" name="text" id="text" placeholder="Enter text..."></textarea>
                            </div>
                            <div class="form-group">
                                <label>Show Type</label>
                                <select class="form-control" name="message_type" id="message_type" style="width: 100%;">
                                    <option value="0">Roll left, respond “BACK” key to exit</option>
                                    <option value="1">pop up window, respond “BACK” key to exit</option>
                                    <option selected value="2">Roll left, do not respond “BACK” key</option>
                                    <option value="3">pop up window, do not respond “BACK” key</option>
                                        
                                </select>
                            </div>
                        </div>
                        <!--/.col-md-4 -->
                        <div class="col-md-2">
                            
                        </div>
                        <!--/.col-md-2 -->
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Date -->
                                    <div class="form-group">
                                        <label>Expire Date</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" name="datepicker_expire_date" id="datepicker_expire_date">
                                        </div><!-- /.input group -->
                                    </div><!-- /.form group -->
                                </div><!-- col -->
                                <div class="col-md-6">
                                    <!-- Time -->
                                    <div class="bootstrap-timepicker">
                                        <div class="form-group">
                                            <label>Expire Time</label>
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
                            <div class="form-group">
                                <label>Show Time Length</label>
                                <input type="number" class="form-control pull-right" name="show_time_length" id="show_time_length" placeholder="Enter show time length ">
                            </div>
                            <div class="form-group">
                                <label>Show Times</label>
                                <input type="number" class="form-control pull-right" name="show_times" id="show_times" placeholder="Enter show times">
                            </div>
                            <div class="form-group">
                                <label>Coverage Rate</label>
                                <input type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="0" name ="coverage_rate" id="coverage_rate"/>
                                <span id="coverage_rate_label">Current Value: <span id="coverage_rate_value">0</span></span>
                            </div>
                            
                            {{-- <input type="hidden" name="operations_id" value="3"> --}}
                        </div>
                        <!--/.col-md-4 -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right">Send</button>
                    </div>
                    <!-- /.box-footer-->
                {!! Form::close() !!}
                <!-- /.form ends-->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col-md-6 -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
@endsection

