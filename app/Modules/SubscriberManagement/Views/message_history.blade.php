@extends('master')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/select2.full.js')}}"></script>
<script src="{{asset('custom/js/utils.js')}}"></script>

<script>
    $(document).ready(function () {

       $('#card_id').select2();
       $('#subscriber_code').select2();

        $('#card_history_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "processing": true,
            "order": [[0, 'asc']]
           
         });
    });
</script>

@endsection


@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Message History
    </h1>
   
</section>
<section class="content">

    <div class="row">
        <div class="col-xs-12">            

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Message History</h3>

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Card ID</label>
                                <select id="card_id" name="card_id" class="form-control select2" >

                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Subscriber Code</label>
                                <select id="subscriber_code" name="subscriber_code" class="form-control select2" >
                                   
                                </select>
                            </div>
                        </div>
                       
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-2">
                            <button id="show" class="btn btn-info">Show</button>
                            <button id="clear_filter" class="btn btn-warning">Clear</button>
                            <br><br>
                        </div>
                    </div>
                    <table id="card_history_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>                                
                                <th>To (Card ID)</th>                                
                                <th>Subscriber Code</th>                                
                                <th>Date</th>                                
                                <th>Message Type</th>                                
                                <th>Message Body</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>CD0003</td>
                                <td>DK1005</td>
                                <td>04/05/15</td>
                                <td>Email</td>
                                <td>Payment Due</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>CD0027</td>
                                <td>DK1002</td>
                                <td>01/05/15</td>
                                <td>OSD</td>
                                <td>Payment Due</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>CD0012</td>
                                <td>DK1015</td>
                                <td>02/05/15</td>
                                <td>Email</td>
                                <td>New Channel Package. Check website for details.</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>CD0015</td>
                                <td>DK1067</td>
                                <td>07/05/15</td>
                                <td>OSD</td>
                                <td>Payment Due</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>CD0005</td>
                                <td>DK1043</td>
                                <td>20/05/15</td>
                                <td>Email</td>
                                <td>New Channel Package. Check website for details.</td>
                            </tr>
                            
                            
                        </tbody>                        
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

</section>
<!-- /.content -->
@endsection

