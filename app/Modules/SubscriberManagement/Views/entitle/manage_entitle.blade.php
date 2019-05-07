@extends('master')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
<!-- Bootstrap date picker -->
<link rel="stylesheet" href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css')}}">
<!-- Bootstrap time picker -->
<link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">

@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('plugins/validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/tooltipster/tooltipster.js')}}"></script>
{{-- Moment --}}
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- Bootstrap date picker -->
<script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<!-- Bootstrap time picker -->
<script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>

<script>
$(document).ready(function () {

    // initialize tooltipster on form input elements
    $('form input, select, textarea').tooltipster({// <-  USE THE PROPER SELECTOR FOR YOUR INPUTs
        trigger: 'custom', // default is 'hover' which is no good here
        onlyOne: false, // allow multiple tips to be open at a time
        position: 'left'  // display the tips to the right of the element
    });

    // initialize validate plugin on the form
    $('#entitle_form').validate({
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
            product: {required: true},
            datepicker_start_date: {required: true},
            timepicker_start_time: {required: true},
            datepicker_end_date: {required: true},
            timepicker_end_time: {required: true}
        },
        messages: {
            product: {required: "Please select a product"},
            datepicker_start_date: {required: "Please select start date"},
            timepicker_start_time: {required: "Please select start time"},
            datepicker_end_date: {required: "Please select end date"},
            timepicker_end_time: {required: "Please select end time"}
        }
    });



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


    // initialize validate plugin on the form
    $('#customer_note_form').validate({
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
            note: {required: true}
        },
        messages: {
            note: {required: "Please enter note"}
        }
    });

});



</script>

<script>
    $(document).ready(function () {
        $('#entitle_list').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{URL::to('/getentitlementhistory')}}",
                "data": {
                    "customer_id": $('#customer_id').val()
                }
            },
            "columns": [
                {"data": "id"},
                {"data": "card.card_id"},
                {"data": "product.name", render:function (data, type, row) {
                    return data + " " + row.product.days;
                }},
                {"data": "start_time"},
                {"data": "end_time"},
                {"data": "Link", name: 'link', orderable: false, searchable: false}
            ],
            "order": [[0, 'asc']]
        });
        
        // Customer note list
        $('#customer_note_list').DataTable({
            "pageLength": 5,
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{URL::to('/getcustomernotes')}}",
                "data": {
                    "customer_id": $('#customer_id').val()
                }
            },
            "columns": [
                {"data": "id"},
                {"data": "note"},
                {"data": "user.name"},
                {"data": "created_at"}
            ],
            "order": [[0, 'asc']]
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
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Entitle
        <small>all entitle list</small>
    </h1>
    
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-solid box-info">
                <div class="box-header">
                  <h3 class="box-title">Customer Details</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="col-sm-2">
                        <strong>Customer Name:</strong>
                        <p class="text-muted">{{$customer_details[0]->name}}</p>
                    </div>
                    <div class="col-sm-2">
                        <strong>Customer Phone:</strong>
                        <p class="text-muted">{{$customer_details[0]->phone}}</p>
                    </div>
                    <div class="col-sm-2">
                        <strong>Customer Address:</strong>
                        <p class="text-muted">House-{{$customer_details[0]->house->house}}, Road-{{$customer_details[0]->house->road->road}}, Sector-{{$customer_details[0]->house->road->sector->sector}}, {{$customer_details[0]->house->road->sector->territory->name}}</p>
                    </div>
                    <div class="col-sm-2">
                        <strong>Distributor:</strong>
                        <p class="text-muted">{{$customer_details[0]->user->name}}</p>
                    </div>
                    <div class="col-sm-2">
                        <strong>Card(s):</strong>
                        <p class="text-muted">{{$card_details[0]->card_id}}
                            @if($card_details[0]->blacklisted == 1)
                                <span class="bg-red-active color-palette">-BLACKLISTED</span>
                            @endif
                        </p>

                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col-xs-12 -->
    </div><!-- /.row -->

    <div class="row">
        <div class="col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Entitle</h3>
                </div><!-- /.box-header -->
                <!-- form starts here -->
                {!! Form::open(array('url' => 'entitle_process', 'id' => 'entitle_form')) !!}
                <input type="hidden" name="customer_id" id="customer_id" value="{{$customer_id}}">
                <div class="box-body">
                    <div class="form-group">
                        <label>Products</label>
                        <select id="product" name="product" class="form-control product_entitle">
                            @foreach($products as $product)
                                @if($product->days === 0)
                                    <option value="{{$product->id}}" class="{{$product->days}}">{{ $product->name }}</option>
                                @else
                                    <option value="{{$product->id}}" class="{{$product->days}}">{{$product->name . ' ' . $product->days }} days</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    @if(Entrust::hasRole('admin'))
                        <div class="row">
                            <div class="col-xs-6">
                                <!-- Date -->
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
                                <!-- Time -->
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
                        </div>row
                        <div class="row">
                            <div class="col-xs-6">
                                <!-- Date -->
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
                                <!-- Time -->
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
                    @endif
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div><!-- /.box-footer -->
                {!! Form::close() !!}
                <!-- /.form ends here -->
            </div><!-- /.box -->
        </div><!-- col-xs-6 -->

        <div class="col-xs-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Entitlement History</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="entitle_list" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Card ID</th>
                                <th>Product</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- col -->
    </div><!-- row -->

    <div class="row">
        <div class="col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Customer Note</h3>
                </div><!-- /.box-header -->
                <!-- form starts here -->
                {!! Form::open(array('url' => 'customer_note_process', 'id' => 'customer_note_form')) !!}
                <input type="hidden" name="customers_id" id="customers_id" value="{{$customer_id}}">
                <div class="box-body">
                    <div class="form-group">
                        <textarea class="form-control" rows="2" id="note" name="note" placeholder="Enter note"></textarea>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div><!-- /.box-footer -->
                {!! Form::close() !!}
                <!-- /.form ends here -->
            </div><!-- /.box -->
        </div><!-- col-xs-6 -->
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">List of Customer Notes</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="customer_note_list" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Note</th>
                                <th>User</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- col -->
    </div><!-- row -->
</section>
<!-- /.content -->

@endsection

