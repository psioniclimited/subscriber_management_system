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

        // Card Table
        var table = $('#all_osd_messages_list').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/getosdmessages')}}",
            "columns": [
                    {"data": "id"},
                    {"data": "card.card_id"},
                    {"data": "message_type"},
                    {"data": "text"},
                    {"data": "show_time_length"},
                    {"data": "show_times"},
                    {"data": "coverage_rate"},
                    {"data": "expired_time"}
            ],
            "order": [[0, 'asc']]
        });

    });
</script>

@endsection


@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        All OSD Messages
        <small>all osd messages list</small>
    </h1>
   
</section>
<section class="content">

    <div class="row">
        <div class="col-xs-12">            

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">OSD Messages list</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="all_osd_messages_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>                                
                                <th>Card ID</th>                                   
                                <th>Message Type</th>                                   
                                <th>Message Text</th>                                   
                                <th>Show Time Length</th>                                   
                                <th>Show Times</th>                                   
                                <th>Coverage Rate</th>                                   
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

