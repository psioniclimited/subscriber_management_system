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
        
        var table = $('#all_products_list').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/getproducts')}}",
            "columns": [
                    {"data": "id"},
                    {"data": "product_id"},
                    {"data": "name"},                    
                    {"data": "days"},                    
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
        All Products
        <small>all products list</small>
    </h1>
   
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">            
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Products list</h3>

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                   
                    <table id="all_products_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>                                
                                <th>Product ID</th>                                
                                <th>Name</th>                                
                                <th>Days</th>                                
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

