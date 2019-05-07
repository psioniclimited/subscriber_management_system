@extends('master')

@section('css')

{{-- Validation --}}
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
{{-- Utils --}}
<script src="{{asset('custom/js/utils.js')}}"></script>
{{-- Page script --}}
<script>
$(document).ready(function () {
    // initialize tooltipster on form input elements
    $('form input, select').tooltipster({// <-  USE THE PROPER SELECTOR FOR YOUR INPUTs
        trigger: 'custom', // default is 'hover' which is no good here
        onlyOne: false, // allow multiple tips to be open at a time
        position: 'left'  // display the tips to the right of the element
    });

    // initialize validate plugin on the form
    $('#add_set_top_box_form').validate({
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
            number: {required: true},
            stb_models_id: {required: true},
            distributors_id: {required: true}
        },
        messages: {
            number: {required: "Please enter box number"},
            stb_models_id: {required: "Please select box model"},
            distributors_id: {required: "Please select a distributor"}
        }
    });

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
    distributor.change(function(){
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
            data: function(params) {
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
    sub_distributor.change(function(){
        $(this).valid(); // trigger validation on this element
    });
    
    //on distributor change initialize sub_distributor
    distributor.change(function(){
        console.log("Distributor Change");
        //clear selected value of distributor
        sub_distributor.val(null).trigger("change");
        $(this).valid(); // trigger validation on this element 
    });
    
    // Add new set top box brand
    $('#submit_set_top_box_brand').click(function(event){    
        event.preventDefault();
        $.post(
            "{{URL::to('/add_set_top_box_brand_process')}}",
            $( "#add_set_top_box_brand_form" ).serialize(),
            function(data){
                if(data.status == 'success'){
                    //append
                    $('#stb_brands_id')
                    .append($("<option></option>")
                        .attr("value", data.id)
                        .text(data.text));

                    // clear input fields
                    $('#set_top_box_brand_modal :input').val('');
                    //close the modal
                    $('#set_top_box_brand_modal').modal('toggle');
                }
            }
        );
    });

    // Load set top box brand data on model modal 
    $('#set_top_box_model_modal').on('show.bs.modal', function(){
        $.get( "{{URL::to('/select/settopboxbrands')}}", function( data ) {
            $('#model_modal_brand').empty();
            $.each(data, function(index, element){

                $('#model_modal_brand')
                    .append($("<option></option>")
                        .attr("value", element.id)
                        .text(element.text));
            });
        });

    });

    // Add new set top box model
    $('#submit_set_top_box_model').click(function(event){    
        event.preventDefault();
        $.post(
            "{{URL::to('/add_set_top_box_model_process')}}",
            $( "#add_set_top_box_model_form" ).serialize(),
            function(data){
                if(data.status == 'success'){
                    //append
                    $('#stb_models_id')
                    .append($("<option></option>")
                        .attr("value", data.id)
                        .text(data.text));

                    // clear input fields
                    $('#set_top_box_model_modal :input').val('');
                    //close the modal
                    $('#set_top_box_model_modal').modal('toggle');
                }
            }
        );
    });

    var stb_brand = $('#stb_brands_id'),
        stb_model = $('#stb_models_id');

    // Set the parameters as an object
    var parameters = {
        placeholder: "Set top box model",
        url: '{{URL::to('/')}}/select/settopboxmodelsbybrand',
        selector_id: stb_model,
        value_id: stb_brand
    }
    // Pass it as a parameter to init_select
    // Initialize select2 on sector
    init_select(parameters);

    //on stb brand change initialize stb model again
    stb_brand.change(function(){
        //clear selected value of stb model
        stb_model.val(null).trigger("change");
        // Set the parameters as an object
        var parameters = {
            placeholder: "Set top box model",
            url: '{{URL::to('/')}}/select/settopboxmodelsbybrand',
            selector_id: stb_model,
            value_id: stb_brand
        }
        
        // Pass it as a parameter to init_select
        // Initialize select2 on stb model
        init_select(parameters);
        $(this).valid(); // trigger validation on this element 
    });
});
</script>

@endsection

@section('side_menu')

@endsection

@section('content')

<!-- Content header -->
<section class="content-header">
    
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Add New Set Top Box</h3>
                </div><!-- /.box-header -->
                <!-- form starts here -->
                {!! Form::open(array('url' => 'add_set_top_box_process', 'id' => 'add_set_top_box_form')) !!}
                <div class="box-body">
                    <div class="form-group">
                        <label>Box Number*</label>
                        <input type="text" class="form-control" id="number" name="number" placeholder="Enter box number">
                    </div>
                    <div class="form-group">
                        <label>Set Top Box Brand*</label>
                        <div class="input-group">
                            <select class="form-control" name="stb_brands_id" id="stb_brands_id">
                                @foreach($set_top_box_brand as $stbb)
                                    <option value="{{$stbb->id}}">{{$stbb->name}}</option>
                                @endforeach
                            </select>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-block btn-info btn-flat" data-toggle="modal" data-target="#set_top_box_brand_modal">...</button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Set Top Box Model*</label>
                        <div class="input-group">
                            <select class="form-control" name="stb_models_id" id="stb_models_id">
                                
                            </select>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-block btn-info btn-flat" data-toggle="modal" data-target="#set_top_box_model_modal">...</button>
                            </span>
                        </div>
                    </div>
                    @if(Entrust::hasRole('admin'))
                        <div class="form-group">
                            <label>Distributor</label>
                            <select class="form-control select2" name="distributors_id" id="distributors_id"></select>
                        </div>
                        <div class="form-group">
                            <label>Sub Distributor</label>
                            <select class="form-control select2" name="sub_distributors_id" id="sub_distributors_id"></select>
                        </div>
                    @elseif(Entrust::hasRole('distributor'))
                        <div class="form-group">
                            <label>Sub Distributor</label>
                            <select class="form-control select2" name="sub_distributors_id" id="sub_distributors_id"></select>
                        </div>
                    @endif                    
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div><!-- /.box-footer -->
                {!! Form::close() !!}
                <!-- /.form ends here -->
            </div><!-- /.box -->
        </div><!-- col -->
    </div><!-- row -->

    <!-- Form for Add New Set Top Box Brand -->
    {!! Form::open(array('url' => 'add_set_top_box_brand_process', 'id' => 'add_set_top_box_brand_form')) !!}
        <!-- Add New Set Top Box Brand Modal -->
        <div class="modal fade" id="set_top_box_brand_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New Set Top Box Brand</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Set Top Box Brand Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter set top box brand name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submit_set_top_box_brand">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!--  Add New Set Top Box Brand Modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for Add New Set Top Box Brand ends here -->

    <!-- Form for Add New Set Top Box Model -->
    {!! Form::open(array('url' => 'add_set_top_box_model_process', 'id' => 'add_set_top_box_model_form')) !!}
        <!-- Add New Set Top Box Model Modal -->
        <div class="modal fade" id="set_top_box_model_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New Set Top Box Model</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Set Top Box Brand</label>
                            <select class="form-control" name="model_modal_brand" id="model_modal_brand">
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="territory_modal">Set Top Box Model Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter set top box model name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submit_set_top_box_model">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!--  Add New Set Top Box Model Modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for Add New Set Top Box Model ends here -->

</section><!-- /.content -->

@endsection

