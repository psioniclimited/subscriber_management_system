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

        // View Sub distributors of a distributor
        var table = $('#sub_distributor_by_distributor_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{URL::to('/getsubdistributorsbydistributor')}}",
                "data": {
                    "distributor_id": $('#distributor_id').val()
                }
            },
            "columns": [
                    {"data": "id"},
                    {"data": "name"},
                    {"data": "email"},                    
                    {"data": "Link", name: 'link', orderable: false, searchable: false},
                    {"data": "Login", name: 'login', orderable: false, searchable: false}
                ],
            "order": [[0, 'asc']]
        });

        // Enable login
        $('#confirm_enable_login').on('shown.bs.modal', function(e) {
            var $modal = $(this),
                sub_distributor_id = e.relatedTarget.id;            
            
            $('#enable_login_modal_form').submit(function(e){
                event.preventDefault();
                $.ajax({
                    cache: false,
                    type: 'POST',
                    url: '{{URL::to('/enable_login_process')}}',
                    data: {
                        sub_distributor_id
                    },
                    success: function(data){
                        //close the modal
                        $('#confirm_enable_login').modal('toggle');

                        // Reload Cards datatable
                        // table.ajax.reload();
                        location.reload();
                    }
                });
            });
        });

        // Disable login
        $('#confirm_disable_login').on('shown.bs.modal', function(e) {
            var $modal = $(this),
                sub_distributor_id = e.relatedTarget.id;            
            
            $('#disable_login_modal_form').submit(function(e){
                event.preventDefault();
                $.ajax({
                    cache: false,
                    type: 'POST',
                    url: '{{URL::to('/disable_login_process')}}',
                    data: {
                        sub_distributor_id
                    },
                    success: function(data){
                        //close the modal
                        $('#confirm_disable_login').modal('toggle');

                        // Reload Cards datatable
                        // table.ajax.reload();
                        location.reload();
                    }
                });
            });
        });

        
    });
</script>

@endsection


@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Sub Distributors of <strong>{{$distributor_name}}</strong>
        <small>sub distributor list</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">            

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Sub Distributor list</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <input type="hidden" name="distributor_id" id="distributor_id" value="{{$distributor_id}}">
                    <table id="sub_distributor_by_distributor_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                                <th>Login</th>
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

    <!-- Enable login modal -->
    <!-- Form for enable login modal  -->
    {!! Form::open(array('url' => 'enable_login_process', 'id' => 'enable_login_modal_form')) !!}
    <div class="modal fade" id="confirm_enable_login" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Enable Login</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure about this?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn bg-olive" id="enable_login">Submit</button>
                </div>
            </div>
            <!-- /. Modal content ends here -->
        </div>
    </div>
    <!-- Allow login modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for allow login modal ends here -->

    <!-- Disable login modal -->
    <!-- Form for disable login modal  -->
    {!! Form::open(array('url' => 'disable_login_process', 'id' => 'disable_login_modal_form')) !!}
    <div class="modal fade" id="confirm_disable_login" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Disable Login</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure about this?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning" id="disable_login">Submit</button>
                </div>
            </div>
            <!-- /. Modal content ends here -->
        </div>
    </div>
    <!-- Disable login modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for disable login modal ends here -->

</section>
<!-- /.content -->
@endsection

