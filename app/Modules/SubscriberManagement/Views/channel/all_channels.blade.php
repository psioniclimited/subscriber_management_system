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

        $('#package').select2();

        var table = $('#all_channels_list').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/getchannels')}}",
            "columns": [
                    {"data": "id"},
                    {"data": "name"},                   
                    {"data": "description"}                   
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
        All Channels
        <small>all channels list</small>
    </h1>
   
</section>
<section class="content">

    <div class="row">
        <div class="col-xs-12">            

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Channels list</h3>

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Package</label>
                                <select id="package" name="package" class="form-control select2" >

                                    
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
                    <table id="all_channels_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>                                
                                <th>Channel Name</th>                                
                                <th>Channel Description</th>                                
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

