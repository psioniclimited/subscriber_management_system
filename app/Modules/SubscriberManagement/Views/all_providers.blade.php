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

       $('#provider_name').select2();

        $('#all_providers_list').DataTable({
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
        All Providers
        <small>all providers list</small>
    </h1>
   
</section>
<section class="content">

    <div class="row">
        <div class="col-xs-12">            

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Providers list</h3>

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Provider Name</label>
                                <select id="provider_name" name="provider_name" class="form-control select2" >

                                    
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label>Card</label>
                                <select id="road" name="road" class="form-control select2" >
                                   
                                </select>
                            </div>
                        </div>
                        --}}
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-2">
                            <button id="show" class="btn btn-info">Show</button>
                            <button id="clear_filter" class="btn btn-warning">Clear</button>
                            <br><br>
                        </div>
                    </div>
                    <table id="all_providers_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>                                
                                <th>Provider Name</th>                                
                                <th>Address</th>                                
                                <th>Phone</th>                                
                                <th>Action</th>                            
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Dish Bangla</td>
                                <td>Uttara, Dhaka</td>
                                <td>01758269853</td>
                                <td>
                                    <a href="#" class="btn btn-xs btn-success" data-toggle="modal" data-target="#on_button_modal"><i class="glyphicon glyphicon-edit"></i> ON</a>
                                    <a href="#" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#off_button_modal"><i class="glyphicon glyphicon-edit"></i> OFF</a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>APCl Dish</td>
                                <td>Uttara, Dhaka</td>
                                <td>01758269853</td>
                                <td>
                                    <a href="#" class="btn btn-xs btn-success" data-toggle="modal" data-target="#on_button_modal"><i class="glyphicon glyphicon-edit"></i> ON</a>
                                    <a href="#" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#off_button_modal"><i class="glyphicon glyphicon-edit"></i> OFF</a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>All Star</td>
                                <td>Uttara, Dhaka</td>
                                <td>01758269853</td>
                                <td>
                                    <a href="#" class="btn btn-xs btn-success" data-toggle="modal" data-target="#on_button_modal"><i class="glyphicon glyphicon-edit"></i> ON</a>
                                    <a href="#" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#off_button_modal"><i class="glyphicon glyphicon-edit"></i> OFF</a>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>SAT Dish4</td>
                                <td>Uttara, Dhaka</td>
                                <td>01758269853</td>
                                <td>
                                    <a href="#" class="btn btn-xs btn-success" data-toggle="modal" data-target="#on_button_modal"><i class="glyphicon glyphicon-edit"></i> ON</a>
                                    <a href="#" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#off_button_modal"><i class="glyphicon glyphicon-edit"></i> OFF</a>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Dish Bangla</td>
                                <td>Uttara, Dhaka</td>
                                <td>01758269853</td>
                                <td>
                                    <a href="#" class="btn btn-xs btn-success" data-toggle="modal" data-target="#on_button_modal"><i class="glyphicon glyphicon-edit"></i> ON</a>
                                    <a href="#" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#off_button_modal"><i class="glyphicon glyphicon-edit"></i> OFF</a>
                                </td>
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

     <!-- On Button Modal -->
    <div class="modal fade" id="on_button_modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ON</h4>
                </div>
                <div class="modal-body">
                 <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Channels list</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                     <table id="all_cards_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>...</th>                                
                                <th>#</th>                                
                                <th>Channel Name</th>                                
                                <th>Package</th>                                
                                                           
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="checkbox" name="gender"></td>
                                <td>1</td>
                                <td>Nat Geo Wild HD^</td>
                                <td>Knowledge Pack</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="gender"></td>
                                <td>2</td>
                                <td>Animal Planet HD World</td>
                                <td>Knowledge Pack</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="gender"></td>
                                <td>3</td>
                                <td>MTV Beats</td>
                                <td>Music</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="gender"></td>
                                <td>4</td>
                                <td>HBO HD</td>
                                <td>English Movies</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="gender"></td>
                                <td>5</td>
                                <td>NewsX HD</td>
                                <td>English News</td>
                            </tr>
                        </tbody>                        
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
                  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="delete_customer">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /. Modal content ends here -->
        </div>
    </div>
    <!-- On Button Modal ends here -->

    <!-- Off Button Modal -->
    <div class="modal fade" id="off_button_modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Deactivate</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure about this ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="delete_customer">Deactivate</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /. Modal content ends here -->
        </div>
    </div>
    <!-- Off Button Modal ends here -->

   


</section>
<!-- /.content -->
@endsection

