@extends('master')
@section('css')
<!-- jvectormap -->
<link rel="stylesheet" href="{{asset('plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}">
<!-- DataTable -->
<link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">  
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- Bootstrap date picker -->
<link rel="stylesheet" href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css')}}">
<!-- Bootstrap time picker -->
<link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
<!-- iCheck 1.0.1 -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
@endsection

@section('scripts')
<!-- Sparkline -->
<script src="{{asset('plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<script src="{{asset('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- ChartJS 1.0.1 -->
<script src="{{asset('plugins/chartjs/Chart.min.js')}}"></script>
{{-- Datatable --}}
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="/vendor/datatables/buttons.server-side.js"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/select2.full.js')}}"></script>
{{-- Moment --}}
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- Bootstrap date picker -->
<script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<!-- Bootstrap time picker -->
<script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<!-- iCheck 1.0.1 -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- Page Script -->
<script>
  $(document).ready(function () {

    
        function load_customer_datatable_with_card() {
            var table = $("#all_customer_list").DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    url: '',
                    data: function (data) {
                        data.customer_with_card_option = $('[name="checkbox_operation"]:checked').val()
                    }
                },
                "columns": [
                {
                    "name": "customers_id",
                    "data": "customers_id",
                    "title": "#",
                    "orderable": true,
                    "searchable": true
                }, 
                {
                    "name": "name",
                    "data": "name",
                    "title": "Name",
                    "orderable": true,
                    "searchable": true
                }, 
                {
                    "name": "phone",
                    "data": "phone",
                    "title": "Phone",
                    "orderable": true,
                    "searchable": true
                }, 
                {
                    "name": "card.card_id",
                    "data": "card.card_id",
                    "title": "Card ID",
                    "orderable": true,
                    "searchable": true
                }, 
                {
                    "name": "user.name",
                    "data": "user.name",
                    "title": "Distributor",
                    "orderable": true,
                    "searchable": true
                },
                {
                    "name": "subdistributor_user.name",
                    "data": "Subdistributor",
                    "title": "Subdistributor",
                    "orderable": true,
                    "searchable": true
                }, 
                {
                    "name": "Entitle_Link",
                    "data": "Entitle_Link",
                    "orderable": false,
                    "searchable": false,
                    "title": "Entitle"
                }],
                "dom": "Bfrtip",
                "buttons": ["csv", "excel", "print", "reset", "reload"]
            });
        }
        function load_customer_datatable_without_card() {
            let table = $("#all_customer_list").DataTable({
                "serverSide": true,
                "processing": true,
                "ajax": {
                    url: '',
                    data: function (data) {
                        data.customer_with_card_option = $('[name="checkbox_operation"]:checked').val()
                    }
                },
                "columns": [
                {
                    "name": "customers_id",
                    "data": "customers_id",
                    "title": "#",
                    "orderable": true,
                    "searchable": true
                }, 
                {
                    "name": "name",
                    "data": "name",
                    "title": "Name",
                    "orderable": true,
                    "searchable": true
                }, 
                {
                    "name": "phone",
                    "data": "phone",
                    "title": "Phone",
                    "orderable": true,
                    "searchable": true
                }, 
                {
                    "name": "user.name",
                    "data": "user.name",
                    "title": "Distributor",
                    "orderable": true,
                    "searchable": true
                },
                {
                    "name": "subdistributor_user.name",
                    "data": "Subdistributor",
                    "title": "Subdistributor",
                    "orderable": true,
                    "searchable": true
                }, 
                {
                    "name": "Entitle_Link",
                    "data": "Entitle_Link",
                    "orderable": false,
                    "searchable": false,
                    "title": "Entitle"
                }],
                "dom": "Bfrtip",
                "buttons": ["csv", "excel", "print", "reset", "reload"]
            });
        }

        

        //Date picker expire date
        $('#datepicker_expire_date').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            startDate: "today"
        });

        //Timepicker expire time
        $("#timepicker_expire_time").timepicker({
          showInputs: false
        });

      $('input:radio[name="checkbox_operation"]').on('ifChanged', function(event){
             if ($('[name="checkbox_operation"]:checked').val() == '1') {
                let table_header_with_card = "<thead>"+
                                "<tr>"+
                                    "<th>#</th>"+
                                    "<th>Name</th>"+
                                    "<th>Phone</th>"+
                                    "<th>Card ID</th>"+
                                    "<th>Distributor</th>"+
                                    "<th>Subdistributor</th>"+
                                    "<th>Entitle</th>"+
                                "</tr>"+
                            "</thead>"+
                            "<tbody> "+                       
                            "</tbody>";
                $('#all_customer_list').DataTable().destroy();
                $('#all_customer_list').html(table_header_with_card);            
                load_customer_datatable_with_card();
            } else if($('[name="checkbox_operation"]:checked').val() == '2') {
                let table_header = "<thead>"+
                                "<tr>"+
                                    "<th>#</th>"+
                                    "<th>Name</th>"+
                                    "<th>Phone</th>"+
                                    "<th>Distributor</th>"+
                                    "<th>Subdistributor</th>"+
                                    "<th>Entitle</th>"+
                                "</tr>"+
                            "</thead>"+
                            "<tbody> "+                       
                            "</tbody>";
                $('#all_customer_list').DataTable().destroy();
                $('#all_customer_list').html(table_header); 
                load_customer_datatable_without_card();
            }
        });

        load_customer_datatable_with_card();
        

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });
  });
  
</script>
@endsection



@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Dashboard</h1>
</section>
<!-- Main content -->
<section class="content">
  @if(Entrust::hasRole('admin'))
  <!-- Info boxes -->
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Users</span>
          <span class="info-box-number">{{ $user_count }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="ion ion-ios-people-outline"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Subscribers</span>
          <span class="info-box-number">{{$customer_count}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-blue"><i class="ion ion-ios-people-outline"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Distributors</span>
          <span class="info-box-number">{{$distributor_count}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-teal"><i class="ion ion-ios-people-outline"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Cards</span>
          <span class="info-box-number">{{$card_count}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Active<br>Subscribers</span>
          <span class="info-box-number">{{$active_customer_count}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="ion ion-ios-people-outline"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Inactive<br>Subscribers</span>
          <span class="info-box-number">{{$inactive_customer_count}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
  @endif

  <!-- Subscriber list Datatable Div Row Start -->
  <!-- Main row -->
    <div class="row">
        <div class="col-xs-12">            

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Subscriber list</h3>
                </div>
                
                <div class="box-body">
                    <div class="form-group">
                        <label>
                            <input type="radio" name="checkbox_operation" class="flat-red" checked value="1">
                            Customers with Cards
                        </label> &nbsp;&nbsp;&nbsp;
                        <label>
                            <input type="radio" name="checkbox_operation" class="flat-red" value="2">
                            Customers without Cards
                        </label> &nbsp;&nbsp;&nbsp;
                    </div>
                    <table id="all_customer_list" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Card ID</th>
                                    <th>Distributor</th>
                                    <th>Subdistributor</th>
                                    <th>Entitle</th>
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
  <!-- Subscriber list Datatable Div Row Ends -->  
</section>
<!-- /.content -->
@endsection