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

        // View Bill Collectors
        var table = $('#all_bill_collector_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/getbillcollectors')}}",
            "columns": [
                    {"data": "id", "name": "users.id"},
                    {"data": "name", "name": "users.name"},
                    {"data": "email", "name": "users.email"},                    
                    {"data": "territory_name", "name": "territory.name"},                    
                    {"data": "Link", name: 'link', orderable: false, searchable: false}
                ],
            "order": [[1, 'asc']]
        });

        // Delete Bill Collector
        $('#confirm_delete').on('show.bs.modal', function(e) {
            var $modal = $(this),
                bill_collector_id = e.relatedTarget.id;

            $('#delete_bill_collector').click(function(e){    
                event.preventDefault();
                $.ajax({
                    cache: false,
                    type: 'POST',
                    url: 'billcollectors/' + bill_collector_id + '/delete',
                    data: bill_collector_id,
                    success: function(data){
                        table.ajax.reload(null, false);
                        $('#confirm_delete').modal('toggle');
                    }
                });
            });
        })
    });
</script>

@endsection


@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Bill Collectors
        <small>all bill collector list</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Users</a></li>
        <li class="active">All Users</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">            

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Bill Collector list</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="all_bill_collector_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Territory</th>
                                
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

     <!-- Delete Customer Modal -->
    <div class="modal fade" id="confirm_delete" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Remove Parmanently</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure about this ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="delete_bill_collector">Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /. Modal content ends here -->
        </div>
    </div>
    <!--  Delete Customer Modal ends here -->

</section>
<!-- /.content -->
@endsection

