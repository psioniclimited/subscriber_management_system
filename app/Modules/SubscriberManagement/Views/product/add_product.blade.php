@extends('master')

@section('css')

{{-- Validation --}}
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">

@endsection

@section('scripts')

{{-- Validation --}}
<script src="{{asset('plugins/validation/dist/jquery.validate.js')}}"></script>
<script src="{{asset('plugins/tooltipster/tooltipster.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<!-- iCheck 1.0.1 -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
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
    $('#add_product_form').validate({
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
            product_id: {required: true},
            name: {required: true}
            
        },
        messages: {
            product_id: {required: "Please enter product id"},
            name: {required: "Please enter product name"}
            
        }
    });

    // Channel list
    var table = $('#all_channels_list').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": false,
        "autoWidth": false,
        "processing": true,
        "order": [[0, 'asc']]
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
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
            <!-- Horizontal Form -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Add New Product</h3>
                </div>
                <!-- /.box-header -->
                <!-- form starts here -->
                {!! Form::open(array('url' => 'add_product_process', 'id' => 'add_product_form')) !!}
                <div class="box-body">
                    <div class="form-group">
                        <label>Product ID*</label>
                        <input type="number" class="form-control" id="product_id" name="product_id" placeholder="Enter product id">
                    </div>
                    <div class="form-group">
                        <label>Product Name*</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name">
                    </div>
                    <div class="form-group">
                        <label>Days</label>
                        <input type="number" class="form-control" name="days" id="days" placeholder="Enter number of days"/>
                    </div>
                    <div class="form-group">
                        <label>Channels</label>
                        <table id="all_channels_list" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th></th>                                
                                    <th>Channel Name</th>                                
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allChannels as $channel)
                                    <tr>
                                      <td><input type="checkbox" class="minimal" name="channel[]" id="channel" value="{{$channel->id}}"></td>
                                      <td><label for="channel_name">{{$channel->name}}</label></td>
                                    </tr>
                                @endforeach
                            </tbody>                        
                        </table>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div><!-- /.box-footer -->
                {!! Form::close() !!}
                <!-- /.form ends here -->
            </div><!-- /.box -->
        </div><!-- col -->
    </div><!-- row -->
</section><!-- /.content -->

@endsection

