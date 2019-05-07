@extends('master')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script>
    $(document).ready(function () {
        // Card Table
        var table = $('#card_entitlement_history_list').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/getcardentitlementhistory')}}",
            "columns": [
                    {"data": "id"},
                    {"data": "card.card_id"},
                    {"data": "start_time"},
                    {"data": "end_time"},
                    {"data": "execution_status", render:function (data, type, row) {
                        if(data === 0){
                            return '<span class="red">Unsuccessful</span>'
                        }
                        else{
                            return '<span>Successful</span>'   
                        }
                    }}
            ],
            "order": [[0, 'asc']]
        });
    });
</script>

@endsection

@section('side_menu')

@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Card Entitlement History</h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">            
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Card Entitlement History List</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="card_entitlement_history_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>                                
                                <th>Card ID</th>                                
                                <th>Start Time</th>                                
                                <th>End Time</th>                                
                                <th>Execution Status</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            
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

