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

        $('#channels').DataTable({
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
        Channels
        <small> channels list</small>
    </h1>
   
</section>
<section class="content">

    <div class="row">
        <div class="col-xs-12">            

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">(Package Name)</h3>

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="channels" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>                                
                                <th>Channel Name</th>                                
                                <th>Channel Category</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Nat Geo Wild HD^</td>
                                <td>Knowledge</td>
                                
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Animal Planet HD World</td>
                                <td>Knowledge</td>
                                
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>MTV Beats</td>
                                <td>Music</td>
                                
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>HBO HD</td>
                                <td>Movies</td>
                                
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>NewsX HD</td>
                                <td>News</td>
                                
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

