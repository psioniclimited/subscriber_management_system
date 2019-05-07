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
        var table = $('#all_set_top_box_list').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/getsettopboxes')}}",
            "columns": [
                    {"data": "id"},
                    {"data": "number"},
                    {"data": "set_top_box_model.name"},             
                    {"data": "set_top_box_model.set_top_box_brand.name"},             
                    {"data": "user.name", orderable: true, searchable: true},
                    {"data": "Subdistributor", "name": "", orderable: true, searchable: true},
                    {"data": "Link", orderable: false, searchable: false}            
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
        All Set Top Boxes
        <small>all set top boxes list</small>
    </h1>
   
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">            
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Set Top Boxes list</h3>

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                   
                    <table id="all_set_top_box_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>                                
                                <th>Set Top Box Number</th>                                
                                <th>Set Top Box Model</th>                                
                                <th>Set Top Box Brand</th>                                
                                <th>Distributor</th>
                                <th>Subdistributor</th>                                
                                <th>Action</th>                                
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

