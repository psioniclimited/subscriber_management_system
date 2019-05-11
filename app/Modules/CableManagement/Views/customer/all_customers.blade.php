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
    <!-- iCheck 1.0.1 -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
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
    <!-- iCheck 1.0.1 -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            function load_customer_datatable_with_card() {
                var table = $("#all_customer_list").DataTable({
                    "serverSide": true,
                    "processing": true,
                    "ajax": {
                        url: '',
                        data: function (data) {
                            data.product_type = $('#product').val();
                            data.active_type = $('#active-type').val();
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
                            "name": "customers.name",
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
                            "name": "address",
                            "data": "address",
                            "title": "Address",
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
                            "orderable": false,
                            "searchable": true
                        },
                        {
                            "name": "Subdistributor",
                            "data": "Subdistributor",
                            "title": "Subdistributor",
                            "orderable": false,
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
                            "name": "Activate",
                            "data": "Activate",
                            "orderable": false,
                            "searchable": false,
                            "title": "Activate/Deactivate"
                        },
                        {
                            "name": "Entitle_Link",
                            "data": "Entitle_Link",
                            "orderable": false,
                            "searchable": false,
                            "title": "Entitle"
                        },
                        {
                            "name": "Pair_link",
                            "data": "Pair_link",
                            "orderable": false,
                            "searchable": false,
                            "title": "Pair"
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
            }

            function load_customer_datatable_without_card() {
                let table = $("#all_customer_list").DataTable({
                    "serverSide": true,
                    "processing": true,
                    "ajax": {
                        url: '',
                        data: function (data) {
                            data.product_type = '0';
                            data.active_type = $('#active-type').val();
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
                            "name": "address",
                            "data": "address",
                            "title": "Address",
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
                            "name": "Activate",
                            "data": "Activate",
                            "orderable": false,
                            "searchable": false,
                            "title": "Activate/Deactivate"
                        },
                        {
                            "name": "Entitle_Link",
                            "data": "Entitle_Link",
                            "orderable": false,
                            "searchable": false,
                            "title": "Entitle"
                        },
                        {
                            "name": "Pair_link",
                            "data": "Pair_link",
                            "orderable": false,
                            "searchable": false,
                            "title": "Pair"
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
            }

            // Pair Customer
            $('#confirm_pair').on('shown.bs.modal', function (e) {
                var $modal = $(this),
                    customer_id = e.relatedTarget.id;
                $('#pair_modal_form').submit(function (e) {
                    var form_data = $("#pair_modal_form").serializeArray();
                    event.preventDefault();
                    $.ajax({
                        cache: false,
                        type: 'POST',
                        url: '{{URL::to('/pair_customer_process')}}',
                        data: {
                            form_data,
                            customer_id
                        },
                        success: function (data) {
                            //close the modal
                            $('#confirm_pair').modal('toggle');
                            // clear input fields
                            $('input').val('');
                            // Reload Cards datatable
                            // table.ajax.reload();
                            location.reload();
                        }
                    });
                });
            });

            // Fingerprint card
            $('#confirm_fingerprint').on('shown.bs.modal', function (e) {
                var $modal = $(this),
                    customer_id = e.relatedTarget.id;

                $('#fingerprint_modal_form').submit(function (e) {
                    event.preventDefault();
                    var form_data = $("#fingerprint_modal_form").serializeArray();
                    $.ajax({
                        cache: false,
                        type: 'POST',
                        url: '{{URL::to("/fingerprint_customer_process")}}',
                        data: {
                            form_data,
                            customer_id
                        },
                        success: function (data) {
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

            // Activate Customer
            $('#activate_customer').on('shown.bs.modal', function (e) {
                var $modal = $(this),
                    customer_id = e.relatedTarget.id;

                $('#activate_customer_modal_form').submit(function (e) {
                    event.preventDefault();
                    $.ajax({
                        cache: false,
                        type: 'POST',
                        url: '{{URL::to('/activate_customer_process')}}',
                        data: {
                            customer_id
                        },
                        success: function (data) {
                            //close the modal
                            $('#activate_customer').modal('toggle');

                            // Reload Cards datatable
                            // table.ajax.reload();
                            location.reload();
                        }
                    });
                });
            });

            // Deactivate Customer
            $('#deactivate_customer').on('shown.bs.modal', function (e) {
                var $modal = $(this),
                    customer_id = e.relatedTarget.id;

                $('#deactivate_customer_modal_form').submit(function (e) {
                    event.preventDefault();
                    var form_data = $("#deactivate_customer_modal_form").serializeArray();
                    $.ajax({
                        cache: false,
                        type: 'POST',
                        url: '{{URL::to('/deactivate_customer_process')}}',
                        data: {
                            customer_id,
                            form_data
                        },
                        success: function (data) {
                            //close the modal
                            $('#deactivate_customer').modal('toggle');
                            // Reload Cards datatable
                            // table.ajax.reload();
                            location.reload();
                        }
                    });

                    return false;
                });

            });

            $('input:radio[name="checkbox_operation"]').on('ifChanged', function (event) {
                if ($('[name="checkbox_operation"]:checked').val() == '1') {
                    let table_header_with_card = "<thead>" +
                        "<tr>" +
                        "<th>#</th>" +
                        "<th>Name</th>" +
                        "<th>Phone</th>" +
                        "<th>Address</th>" +
                        "<th>Card ID</th>" +
                        "<th>Distributor</th>" +
                        "<th>Subdistributor</th>" +
                        "<th>Action</th>" +
                        "<th>Activate/Deactivate</th>" +
                        "<th>Entitle</th>" +
                        "<th>Pair</th>" +
                        "<th>Fingerprint</th>" +
                        "</tr>" +
                        "</thead>" +
                        "<tbody> " +
                        "</tbody>";
                    $('#all_customer_list').DataTable().destroy();
                    $('#all_customer_list').html(table_header_with_card);
                    load_customer_datatable_with_card();
                } else if ($('[name="checkbox_operation"]:checked').val() == '2') {
                    let table_header = "<thead>" +
                        "<tr>" +
                        "<th>#</th>" +
                        "<th>Name</th>" +
                        "<th>Phone</th>" +
                        "<th>Address</th>" +
                        "<th>Distributor</th>" +
                        "<th>Subdistributor</th>" +
                        "<th>Action</th>" +
                        "<th>Activate/Deactivate</th>" +
                        "<th>Entitle</th>" +
                        "<th>Pair</th>" +
                        "<th>Fingerprint</th>" +
                        "</tr>" +
                        "</thead>" +
                        "<tbody> " +
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

            var product = $('#product');

            /* get Distributors for Select2 */
            product.select2({
                data: [],
                placeholder: "Select a Product",
                allowClear: true,
                ajax: {
                    dataType: 'json',
                    url: "{{URL::to('/')}}/auto/product",
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
                var activeType= $('#active-type').val();
                e.preventDefault();
                // Reload datatable
                if ($('[name="checkbox_operation"]:checked').val() == '1') {
                    $('#all_customer_list').DataTable().destroy();
                    // $('#all_customer_list').html(table_header_with_card);
                    load_customer_datatable_with_card();


                } else {
                    $('#all_customer_list').DataTable().destroy();
                    // $('#all_customer_list').html(table_header_with_card);
                    load_customer_datatable_without_card();
                }

                // table.ajax.reload();

            });

            $('#clear_filter').on('click', function(e){
                e.preventDefault();
                // Clear the select2 fields
                $('#product').val(null).trigger("change");

                // Reload datatable
                if ($('[name="checkbox_operation"]:checked').val() == '1') {
                    console.log('working on it');
                    $('#all_customer_list').DataTable().destroy();
                    // $('#all_customer_list').html(table_header_with_card);
                    load_customer_datatable_with_card();
                } else {
                    $('#all_customer_list').DataTable().destroy();
                    // $('#all_customer_list').html(table_header_with_card);
                    load_customer_datatable_without_card();
                }

                // Set target bill to 0
                $('#target_bill').html(0);
            });

        });
    </script>

@endsection


@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Subscribers
            <small>all subscriber list</small>
        </h1>
    </section>


    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Subscriber list</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Products</label>
                                    <select id="product" name="product" class="form-control select2" >

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Active/Deactivate Customers</label>
                                    <select id="active-type" name="active-type" class="form-control" >
                                        <option value="1">Active</option>
                                        <option value="0">Deactivate</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <button id="show" class="btn btn-info">Show</button>
                                <button id="clear_filter" class="btn btn-warning">Clear</button>
                                <br>
                            </div>
                        </div>
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
                                <th>Address</th>
                                <th>Card ID</th>
                                <th>Distributor</th>
                                <th>Subdistributor</th>
                                <th>Action</th>
                                <th>Activate/Deactivate</th>
                                <th>Entitle</th>
                                <th>Pair</th>
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

        <!-- Pair Modal -->
        <!-- Form for Pair Modal  -->
        {!! Form::open(array('url' => 'pair_customer_process', 'id' => 'pair_modal_form')) !!}
        <div class="modal fade" id="confirm_pair" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Pair Customer</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pair Duration</label>
                            <input type="number" class="form-control" name="pair_duration" id="pair_duration"
                                   placeholder="Enter pair duration"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" id="pair_customer">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!-- Pair Modal ends here -->
        {!! Form::close() !!}
    <!-- /.  Form for Pair Modal ends here -->

        <!-- Fingerprint Modal -->
        <!-- Form for Fingerprint Modal  -->
        {!! Form::open(array('url' => 'fingerprint_customer_process', 'id' => 'fingerprint_modal_form')) !!}
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
                            <input type="number" class="form-control" name="duration" id="duration"
                                   placeholder="Enter fingerprint duration"/>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Fingerprint Expire Date</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" name="datepicker_expire_date"
                                               id="datepicker_expire_date">
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div><!-- col -->

                            <div class="col-xs-6">
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <label>Fingerprint Expire Time</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control timepicker"
                                                   name="timepicker_expire_time" id="timepicker_expire_time">
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

        <!-- Activate customer modal -->
        <!-- Form for activate customer modal  -->
        {!! Form::open(array('url' => 'activate_customer_process', 'id' => 'activate_customer_modal_form')) !!}
        <div class="modal fade" id="activate_customer" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Activate Customer</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure about this?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-olive" id="submit_activate">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!-- Activate customer modal ends here -->
        {!! Form::close() !!}
    <!-- /.  Form for activate customer modal ends here -->

        <!-- Deactivate customer modal -->
        <!-- Form for deactivate customer modal  -->
        {!! Form::open(array('url' => 'deactivate_customer_process', 'id' => 'deactivate_customer_modal_form')) !!}
        <div class="modal fade" id="deactivate_customer" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Deactivate Customer</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure about this?</p>
                        <p>Do you want to deactivate customer temporarily or permanently? Please mention it below.</p>
                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="deactivate_option" id="deactivate_option_1"
                                           value="temporarily_deactivate" checked>
                                    Temporarily Deactivate
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="deactivate_option" id="deactivate_option_2"
                                           value="permanently_deactivate">
                                    Permanently Deactivate
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning" id="submit_deactivate">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!-- Deactivate customer modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for deactivate customer modal ends here -->

    </section>
    <!-- /.content -->
@endsection

