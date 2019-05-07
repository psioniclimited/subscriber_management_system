@extends('master')
@section('css')
    {{-- Tooltipster --}}
    <link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
    {{-- Select2 --}}
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
@endsection

@section('scripts')
    {{-- Validation --}}
    <script src="{{asset('plugins/validation/dist/jquery.validate.js')}}"></script>
    <script src="{{asset('plugins/tooltipster/tooltipster.js')}}"></script>
    {{-- Select2 --}}
    <script src="{{asset('plugins/select2/select2.full.min.js')}}"></script>
    {{-- Custom delete territory, sector, road & house --}}
    <script src="{{asset('custom/js/delete_territory_sector_road_house.js')}}"></script>
    <script>
        $(document).ready(function () {

            // initialize tooltipster on form input elements
            $('form input, select').tooltipster({// <-  USE THE PROPER SELECTOR FOR YOUR INPUTs
                trigger: 'custom', // default is 'hover' which is no good here
                onlyOne: false, // allow multiple tips to be open at a time
                position: 'left'  // display the tips to the right of the element
            });

            // initialize validate plugin on the form
            $('#add_customer_form').validate({
                errorPlacement: function (error, element) {

                    var lastError = $(element).data('lastError'),
                        newError = $(error).text();

                    $(element).data('lastError', newError);

                    if (newError !== '' && newError !== lastError) {
                        $(element).tooltipster('content', newError);
                        $(element).tooltipster('show');
                    }
                },
                success: function (label, element) {
                    $(element).tooltipster('hide');
                },
                rules: {
                    name: {required: true},
                    phone: {required: true},
                    subscription_types_id: {required: true},
                    distributors_id: {required: true},
                    territory: {required: true}
                },
                messages: {
                    name: {required: "Please enter customer name"},
                    phone: {required: "Please enter phone number"},
                    subscription_types_id: {required: "Please select subscription type"},
                    distributors_id: {required: "Please select a distributor"},
                    territory: {required: "Please enter the address"},
                }
            });


            /* ..... DELETE TERRITORY, SECTOR, ROAD & HOUSE ..... */

            var distributor = $('#distributors_id'),
                sub_distributor = $('#sub_distributors_id');
            /* get Distributors for Select2 */
            distributor.select2({
                placeholder: "Select a Distributor",
                allowClear: true,
                ajax: {
                    dataType: 'json',
                    url: "{{URL::to('/')}}/select/distributors",
                    delay: 250,
                    data: function (params) {
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
            distributor.change(function () {
                $(this).valid(); // trigger validation on this element
            });

            /* get Sub Distributors for Select2 */
            sub_distributor.select2({
                placeholder: "Select a Sub Distributor",
                allowClear: true,
                ajax: {
                    dataType: 'json',
                    url: "{{URL::to('/')}}/select/subdistributors",
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term,
                            distributor_id: $('#distributors_id').val(),
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
            sub_distributor.change(function () {
                $(this).valid(); // trigger validation on this element
                card.val(null).trigger("change");
                set_top_box.val(null).trigger("change");
            });

            //on distributor change initialize sub_distributor
            distributor.change(function () {
                //clear selected value of distributor
                sub_distributor.val(null).trigger("change");
                card.val(null).trigger("change");
                set_top_box.val(null).trigger("change");
                $(this).valid(); // trigger validation on this element
            });


            // Card ID
            var card = $('#card_id');
            card.select2({
                placeholder: "Select a card id",
                allowClear: true,
                ajax: {
                    dataType: 'json',
                    url: "{{URL::to('/')}}/auto/allcardids",
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term,
                            distributor_id: $('#distributors_id').val(),
                            sub_distributor_id: $('#sub_distributors_id').val(),
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
            card.change(function () {
                $(this).valid();
            });

            // Set top box serial numbers
            var set_top_box = $('#set_top_box_sn');
            set_top_box.select2({
                placeholder: "Select a set top box",
                allowClear: true,
                ajax: {
                    dataType: 'json',
                    url: "{{URL::to('/')}}/select/settopboxes",
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term,
                            distributor_id: $('#distributors_id').val(),
                            sub_distributor_id: $('#sub_distributors_id').val(),
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
            set_top_box.change(function () {
                $(this).valid();
            })


        });

    </script>

@endsection

@section('side_menu')

@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Create Subscriber</h3>
            </div>
            <!-- /.box-header -->
            {{-- Form starts here --}}
            {!! Form::open(array('url' => 'create_customer_process', 'id' => 'add_customer_form', 'class' => 'form-horizontal')) !!}
            <div class="box-body">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Subscriber Name*</label>
                        <input type="text" class="form-control" name="name" id="customer_name"
                               placeholder="Subscriber Name" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label>Phone*</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter phone"
                               value="{{ old('phone') }}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label>National Identity Card Number</label>
                        <input type="text" class="form-control" name="nid" id="nid" placeholder="Enter nid number">
                    </div>
                    <div class="form-group">
                        <label>Passport Number</label>
                        <input type="text" class="form-control" name="passport" id="passport"
                               placeholder="Enter passport number">
                    </div>
                    <div class="form-group">
                        <label>Subscription Type*</label>
                        <select class="form-control" name="subscription_types_id" id="subscription_type"
                                style="width: 100%;">
                            @foreach($subscription_types as $subscription_type)
                                <option value="{{$subscription_type->id}}">{{$subscription_type->name}}</option>
                            @endforeach

                        </select>
                    </div>
                    @if(Entrust::hasRole('admin'))
                        <div class="form-group">
                            <label>Distributor*</label>
                            <select class="form-control select2" name="distributors_id" id="distributors_id"></select>
                        </div>
                        <div class="form-group">
                            <label>Sub Distributor</label>
                            <select class="form-control select2" name="sub_distributors_id"
                                    id="sub_distributors_id"></select>
                        </div>
                    @elseif(Entrust::hasRole('distributor'))
                        <div class="form-group">
                            <label>Sub Distributor</label>
                            <select class="form-control select2" name="sub_distributors_id"
                                    id="sub_distributors_id"></select>
                        </div>
                    @endif
                </div>
                <!-- /.col -->
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Card ID</label>
                        <select class="form-control select2" name="card_id" id="card_id">

                        </select>
                    </div>
                    <div class="form-group">
                        <label>Set Top Box Serial Number</label>
                        <select class="form-control select2" name="set_top_box_sn" id="set_top_box_sn">

                        </select>
                    </div>
                    <div class="form-group">
                        <label>Territory*</label>
                        <input type="text" class="form-control" name="territory" id="territory"
                               placeholder="Enter Address">
                    </div>

                </div>
                <!-- /.col -->
            </div>
            <!-- /.box-body -->
            <input type="hidden" name="active" value="1">

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Submit</button>
            </div>
            <!-- /.box-footer -->
            {!! Form::close() !!}
            {{-- Form ends here --}}
        </div>
        <!-- /.box -->

        <!-- All Modals -->

        <!-- All Modals end here -->


    </section>
@endsection


