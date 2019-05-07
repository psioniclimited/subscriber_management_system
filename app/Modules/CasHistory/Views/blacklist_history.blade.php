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
        var table = $('#blacklist_history_list').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/getblacklisthistory')}}",
            "columns": [
                    {"data": "id"},
                    {"data": "card.card_id"},
                    {"data": "expired_time"}
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
    <h1>Blacklist History</h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">            
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Blacklist History List</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="blacklist_history_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>                                
                                <th>Card ID</th>                                
                                <th>Expired Time</th>                                
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

