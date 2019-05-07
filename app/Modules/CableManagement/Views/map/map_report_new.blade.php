0
@extends('master')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">

@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
{{-- Date Range Picker --}}
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/select2.full.js')}}"></script>
{{-- Google Map --}}

<script>
    $(document).ready(function () {
        //Date range picker
        $('#daterange').daterangepicker();

        $('#bill_collector').select2({
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

 


        $('#show').on('click', function(e){
            e.preventDefault();
             $.get( "{{URL::to('/')}}/mapdata")
                  .done(function( data ) {
        var custLat = [];
        var custLong = [];                     
                    $.each(data, function(index, value){
                        console.log(value.lat);
                        custLat.push(value.lat);
                        custLong.push(value.lon);
                    });

        // var stavanger = new google.maps.LatLng(23.901721, 90.391389);
        var amsterdam = new google.maps.LatLng(custLat[0], custLong[0]);
        // var london = new google.maps.LatLng(23.902373, 90.388338);

      


        var mapCanvas = document.getElementById("map");
        var mapOptions = {center: amsterdam, zoom: 25,mapTypeId: google.maps.MapTypeId.HYBRID};
        var map = new google.maps.Map(mapCanvas,mapOptions);


        
        var markers = [];
        var infowindows = [];
        var linePoints = [];


        for(let i=0; i<custLat.length; i++) {   

          markers[i] = new google.maps.Marker({position:new google.maps.LatLng(custLat[i], custLong[i]),map:map});
          linePoints.push(new google.maps.LatLng(custLat[i], custLong[i]));
          infowindows[i] = new google.maps.InfoWindow({
              content: "<b>Hello World!</b>"
          });

          google.maps.event.addListener(markers[i], 'click', function() {
            infowindows[i].open(map, markers[i]);
          });

        }

        var flightPath = new google.maps.Polyline({
            path: linePoints,
            strokeColor: "#0000FF",
            strokeOpacity: 0.8,
            strokeWeight: 2
        });

        flightPath.setMap(map);







                });
            // var bill_collector_id = $('#bill_collector').val();
            // var daterange_id = $('#daterange').val();
            
            // if(bill_collector_id != null || daterange_id != null){
               
            // }
            // else{
            // }


        });     



        $('#clear_filter').on('click', function(e){
            e.preventDefault();
            $('#bill_collector').val(null).trigger("change");
            $('#daterange').val(null).trigger("change");

        });

    });
</script>


@endsection


@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Map Report
    </h1>
</section>


<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Map Report</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">  
          <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Bill Collector</label>
                        <select name="bill_collector" class="form-control select2" id="bill_collector" style="width: 100%;">

                        </select>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                    <!-- Date range -->
                    <div class="form-group">
                        <label>Date range:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right" id="daterange">
                        </div>
                        <!-- /.input group -->
                    </div>
                    <!-- /.form group -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-2">
                    <button type="submit" id="show" class="btn btn-info">Submit</button>
                    <button id="clear_filter" class="btn btn-warning">Clear</button>
                    <br><br>
                </div>
            </div>
            <div id="map" style="width:100%;height:500px"></div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        </div>
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->

<script src="https://maps.googleapis.com/maps/api/js"></script>
@endsection

