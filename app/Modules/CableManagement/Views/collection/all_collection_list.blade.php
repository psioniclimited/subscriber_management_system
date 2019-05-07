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

<script>
    $(document).ready(function () {
     
        // Collection list datatable
        $('#all_collection_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/getcollectionlist')}}",
            "columns": [
                    {"data": "id"}, 
                    {"data": "customer_code", "name": "customer_code"},
                    {"data": "name", "name": "name"},
                    {"data": "phone", "name": "phone"},                    
                    {"data": "timestamp"},                    
                    {"data": "total"},
                    {"data": "bill_collector_name", "name": "bill_collector_name"},                    
                    {"data": "Link", name: 'link', orderable: false, searchable: false}                  
                   
            ],
            "order": [[0, 'asc']]
           
        });
            
        // Select2 fields
        var bill_collector = $('#bill_collector'), 
            territory = $('#territory'),
            sector = $('#sector');


        // Bill collector initialized
        bill_collector.select2({
            placeholder: "Select a bill collector",
            allowClear: true,
            ajax: {
                dataType: 'json',
                url: "{{URL::to('/')}}/auto/allbillcollectors",
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term,
                        page: params.page
                    }
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            } 
        });

        // Territory initialized
        territory.select2({
            placeholder: "Enter Territory",
            allowClear: true,
            ajax: {
                dataType: 'json',
                url: "{{URL::to('/')}}/auto/allterritory",
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term,
                        page: params.page
                    }
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            } 
        });



        var init_select = function(parameters){
            var custom_url = "{{URL::to('/')}}/";
            var placeholder_text = 'Enter ';
                parameters.selector_id.select2({
                    allowClear: true,
                    placeholder: placeholder_text + parameters.placeholder,
                    ajax: {
                        dataType: 'json',
                        url: custom_url + parameters.url,
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term,
                                value_term: parameters.value_id.val(),
                                page: params.page
                            }
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data,
                                pagination: {
                                    more: (params.page * 30) < data.total_count
                                }
                            };
                        },
                        cache: true
                    }
                });
        }  

        

       
        // Set the parameters as an object
        var parameters = {
            placeholder: "Sector",
            url: 'auto/sector',
            selector_id: sector,
            value_id: territory
        }
        
        // Pass it as a parameter to init_select
        // Initialize select2 on sector field
        init_select(parameters);


        bill_collector.change(function(){
            territory.prop("disabled", true);
            sector.prop("disabled", true);
        });

        territory.change(function(){
            bill_collector.prop("disabled", true);
        });

        sector.change(function(){
            // bill_collector.prop("disabled", true);
        });

        // Show button 
        $('#show').on('click', function(e){
            e.preventDefault();
            $bill_collector_id = $('#bill_collector').val();
            $territory_id = $('#territory').val();
            $sector_id = $('#sector').val();
            
            if($bill_collector_id != null || $territory_id != null || $sector_id != null){
                var table = $('#all_collection_list').DataTable( {
                    "paging": true,
                    "lengthChange": false,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "processing": true,
                    "serverSide": true,
                    "destroy": true,
                    "ajax": {
                       "url": "{{URL::to('/getfilteredcollectiondata')}}", 
                       "data": {
                           "bill_collector_id": $bill_collector_id,
                           "territory_id": $territory_id,
                           "sector_id": $sector_id
                       }
                    },
                    "columns": [
                            {"data": "id"}, 
                            {"data": "customer_code", "name": "customer_code"},
                            {"data": "name", "name": "name"},
                            {"data": "phone", "name": "phone"},                    
                            {"data": "timestamp"},                    
                            {"data": "total"},
                            {"data": "bill_collector_name", "name": "bill_collector_name"},                    
                            {"data": "Link", name: 'link', orderable: false, searchable: false}                  
                           
                    ],
                    "order": [[0, 'asc']]
                } );

            }
            else{
                // Do nothing
            }

         
        });

        // Clear button
        $('#clear_filter').on('click', function(e){
            e.preventDefault();
            // Clear the select2 fields
            bill_collector.val(null).trigger("change");
            territory.val(null).trigger("change");
            sector.val(null).trigger("change");
            // Enable the select2 fields
            bill_collector.prop("disabled", false);
            territory.prop("disabled", false);
            sector.prop("disabled", false);


            // get collection list without filters
            $('#all_collection_list').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "ajax": "{{URL::to('/getcollectionlist')}}",
                "columns": [
                        {"data": "id"}, 
                        {"data": "customer_code", "name": "customer_code"},
                        {"data": "name", "name": "name"},
                        {"data": "phone", "name": "phone"},                    
                        {"data": "timestamp"},                    
                        {"data": "total"},
                        {"data": "bill_collector_name", "name": "bill_collector_name"},                    
                        {"data": "Link", name: 'link', orderable: false, searchable: false}                  
                       
                ],
                "order": [[0, 'asc']]
           
            });

           
        });
       

     

        // $('a[id^="map_value"]').click(function(){
        //     console.log('test');
        // });
        // $('.btn-primary a').click(function(){
        //     console.log('test');
        // });
        // $("[id^=map_value]").click(function(event){
        //     event.preventDefault();
        //     var href = $('this').attr('href');
        //     alert(href);
        // });
        
       
    });
</script>

@endsection



@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Total Collection
        <small>all collection list</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Users</a></li>
        <li class="active">All Users</li>
    </ol>
</section>
<section class="content">

    <div class="row">
        <div class="col-xs-12">            

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Collection list</h3>

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Bill Collector</label>
                                <select id="bill_collector" name="bill_collector" class="form-control select2" >
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Territory</label>
                                <select id="territory" name="territory" class="form-control select2" >
                                  
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sector</label>
                                <select id="sector" name="sector" class="form-control select2" >
                                       
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-2">
                            <button id="show" class="btn btn-info">Show</button>
                            <button id="clear_filter" class="btn btn-warning">Clear</button>
                            <br>
                        </div>
                    </div>
                    <table id="all_collection_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>                                
                                <th>Customer Code</th>                                
                                <th>Customer Name</th>                                
                                <th>Phone</th>                                
                                <th>Timestamp</th>                                
                                <th>Total</th>
                                <th>Collected By</th>
                                <th>View</th>                                
                            </tr>
                        </thead>
                        <tbody>                            
                            <!-- collection list -->
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

