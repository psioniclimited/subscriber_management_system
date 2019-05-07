@extends('master')

@section('css')
<!-- DataTable -->
<link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css"> 
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- Bootstrap date picker -->
<link rel="stylesheet" href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css')}}">
<!-- Bootstrap time picker -->
<link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
@endsection

@section('scripts')
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
<script>
    $(document).ready(function () {

        let table = $("#all_cards_list").DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                url: '',
                data: function (data) {
                    data.distributor_id = $('#distributors_id').val();
                    data.sub_distributor_id = $('#sub_distributors_id').val();
                }
            },
            "columns": [{
                "name": "card_id",
                "data": "card_id",
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
                "name": "Link",
                "data": "Link",
                "title": "Action",
                "orderable": false,
                "searchable": false
            }, 
            {
                "name": "Blacklist_link",
                "data": "Blacklist_link",
                "orderable": false,
                "searchable": false,
                "title": "Blacklist"
            },
            {
                "name": "Fingerprint_link",
                "data": "Fingerprint_link",
                "orderable": false,
                "searchable": false,
                "title": "Fingerprint"
            }],
            "dom": "Bfrtip",
            "buttons": ["csv", "excel", "print", "reset", "reload"]
        });

        // Blacklist card
        $('#confirm_blacklist').on('shown.bs.modal', function(e) {
            var $modal = $(this),
                card_id = e.relatedTarget.id;            

            $('#blacklist_modal_form').submit(function(e){
                var form_data = $("#blacklist_modal_form").serializeArray();
                event.preventDefault();
                $.ajax({
                    cache: false,
                    type: 'POST',
                    url: '{{URL::to('/blacklist_card_process')}}',
                    data: {
                        form_data,
                        card_id
                    },
                    success: function(data){
                        //close the modal
                        $('#confirm_blacklist').modal('toggle');
                        // clear input fields
                        $('input').val('');
                        // Reload Cards datatable
                        // table.ajax.reload();
                        location.reload();
                    }
                });
            });
        });

        // Unblacklist card
        $('#confirm_unblacklist').on('shown.bs.modal', function(e) {
            var $modal = $(this),
                card_id = e.relatedTarget.id;            

            $('#unblacklist_modal_form').submit(function(e){
                event.preventDefault();
                $.ajax({
                    cache: false,
                    type: 'POST',
                    url: '{{URL::to('/unblacklist_card_process')}}',
                    data: {
                        card_id
                    },
                    success: function(data){
                        //close the modal
                        $('#confirm_unblacklist').modal('toggle');
                        // Reload Cards datatable
                        // table.ajax.reload();
                        location.reload();
                    }
                });
            });
        });

        // Fingerprint card
        $('#confirm_fingerprint').on('shown.bs.modal', function(e) {
            var $modal = $(this),
                card_id = e.relatedTarget.id;            

            $('#fingerprint_modal_form').submit(function(e){
                event.preventDefault();
                var form_data = $("#fingerprint_modal_form").serializeArray();
                $.ajax({
                    cache: false,
                    type: 'POST',
                    url: '{{URL::to('/fingerprint_card_process')}}',
                    data: {
                        form_data,
                        card_id
                    },
                    success: function(data){
                        //close the modal
                        $('#confirm_fingerprint').modal('toggle');
                        // Reload Cards datatable
                        // table.ajax.reload();
                        location.reload();
                    }
                });
            });
        });

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

    });
</script>

@endsection



@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        All Cards
        <small>all cards list</small>
    </h1>
   
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">            
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Cards list</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="all_cards_list" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Card ID</th>                                
                                    <th>Distributor</th>                                
                                    <th>Subdistributor</th>
                                    <th>Action</th>
                                    <th>Blacklist</th>                                
                                    <th>Fingerprint</th>
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

    <!-- Blacklist Modal -->
    <!-- Form for Blacklist Card Modal  -->
    {!! Form::open(array('url' => 'blacklist_card_process', 'id' => 'blacklist_modal_form')) !!}
    <div class="modal fade" id="confirm_blacklist" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Blacklist Card</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Blacklist Duration (In days)</label>
                        <input type="number" class="form-control" name="blacklist_duration" id="blacklist_duration" placeholder="Enter blacklist duration"/>
                    </div>
                    <div class="form-group">
                        <label>Note</label>
                        <input type="text" class="form-control" name="note" id="note" placeholder="Enter note"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" id="blacklist_card">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /. Modal content ends here -->
        </div>
    </div>
    <!-- Blacklist Modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for Blacklist Card Modal ends here -->

    <!-- Unblacklist Modal -->
    <!-- Form for Unblacklist Card Modal  -->
    {!! Form::open(array('url' => 'unblacklist_card_process', 'id' => 'unblacklist_modal_form')) !!}
    <div class="modal fade" id="confirm_unblacklist" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Unblacklist Card</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure about this ?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning" id="unblacklist_card">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /. Modal content ends here -->
        </div>
    </div>
    <!-- Unblacklist Modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for Unblacklist Card Modal ends here -->

    <!-- Fingerprint Modal -->
    <!-- Form for Fingerprint Modal  -->
    {!! Form::open(array('url' => 'fingerprint_card_process', 'id' => 'fingerprint_modal_form')) !!}
    <div class="modal fade" id="confirm_fingerprint" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Fingerprint Card</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Fingerprint Duration</label>
                        <input type="number" class="form-control" name="duration" id="duration" placeholder="Enter fingerprint duration"/>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Fingerprint Expire Date</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" name="datepicker_expire_date" id="datepicker_expire_date">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div><!-- col -->
                        
                        <div class="col-xs-6">
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Fingerprint Expire Time</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control timepicker" name="timepicker_expire_time" id="timepicker_expire_time">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div><!-- timepicker -->
                        </div><!-- col -->
                    </div><!-- row -->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info" id="fingerprint_card">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /. Modal content ends here -->
        </div>
    </div>
    <!-- Fingerprint Modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for Fingerprint Modal ends here -->


</section>
<!-- /.content -->
@endsection

