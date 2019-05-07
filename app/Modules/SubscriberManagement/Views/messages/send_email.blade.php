@extends('master')

@section('css')
{{-- Validation --}}
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
{{-- Date Picker --}}
<link rel="stylesheet" href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css')}}">
@endsection

@section('scripts')
{{-- Validation --}}
<script src="{{asset('plugins/validation/dist/jquery.validate.js')}}"></script>
<script src="{{asset('plugins/tooltipster/tooltipster.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/select2.full.js')}}"></script>
{{-- Date Picker --}}
<script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>

<script>
    $(document).ready(function () {

        // initialize tooltipster on form input elements
        $('form textarea, input, select').tooltipster({// <-  USE THE PROPER SELECTOR FOR YOUR INPUTs
            trigger: 'custom', // default is 'hover' which is no good here
            onlyOne: false, // allow multiple tips to be open at a time
            position: 'left'  // display the tips to the right of the element
        });

        // initialize validate plugin on the form
        $('#send_email_form').validate({
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

        $('#package').select2();

        $('#all_channels_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "processing": true,
            "order": [[0, 'asc']]
           
         });

        //Date picker Start Date
        $('#datepicker_start_date').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            startDate: "today"
        });

         //Date picker Expire Date
        $('#expired_time').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            startDate: "today"
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

    });
</script>

@endsection


@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Send Email
    </h1>
   
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">

           <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Send Email</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(array('url' => 'send_email_process', 'id' => 'send_email_form')) !!}
              <div class="box-body">
                <div class="form-group">
                  <label>To (Card ID)</label>
                  <select class="form-control select2" name="cards_id" id="cards_id" style="width: 100%;"></select>
                </div>
                <div class="form-group">
                  <label>From</label>
                  <input type="text" class="form-control pull-right" name="sender_name" id="sender_name">
                </div>
                <div class="form-group">
                    <label>Body</label>
                    <textarea class="form-control" rows="3" id="message_content" name="message_content" placeholder="Enter text..."></textarea>
                </div>
                
               <div class="form-group">
                    <label>Expire Date</label>

                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right" id="expired_time" name="expired_time">
                    </div>
                    <!-- /.input group -->
              </div>
            </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Send</button>
              </div>
            {!! Form::close() !!}
            <!-- /.form ends-->
          </div>
          <!-- /.box -->
        </div>
    </div>


    
</section>
<!-- /.content -->
@endsection

