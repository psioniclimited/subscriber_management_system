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
$(document).ready(function() {

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
            email: {required: true},
            subscription_types_id: {required: true},
            distributors_id: {required: true},
            territory_id: {required: true},
            sectors_id: {required: true},
            roads_id: {required: true},
            houses_id: {required: true}
        },
        messages: {
            name: {required: "Please enter customer name"},
            phone: {required: "Please enter phone number"},
            email: {required: "Please enter email"},
            subscription_types_id: {required: "Please select subscription type"},
            distributors_id: {required: "Please select a distributor"},
            territory_id: {required: "Please select territory"},
            sectors_id: {required: "Please select sector"},
            roads_id: {required: "Please select road"},
            houses_id: {required: "Please select house"}
        }
    });

	var territory = $('#territory'),
		sector = $('#sector'),
		road = $('#road'),
		house = $('#house');

  	/**
  	 * [init_select initializes select2 on respective select fields and loads data from backend]
  	 * @param  {[object]} parameters [Parameters are placeholder, url, selector_id, value_id]
  	 * @return {[type]}            [description]
  	 */
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
	// Initialize select2 on sector
  	init_select(parameters);

  	//on sector change initialize road
  	sector.change(function(){
	  	//clear selected value of road
	  	road.val(null).trigger("change");
		// Set the parameters as an object
	  	var parameters = {
			placeholder: "Road",
			url: 'auto/road',
			selector_id: road,
			value_id: sector
		}
		// Pass it as a parameter to init_select
		// Initialize select2 on road
	  	init_select(parameters);
	  	$(this).valid(); // trigger validation on this element 
  	});

  	//on road change initialize house
  	road.change(function(){
	  	//clear selected house value
	  	house.val(null).trigger("change");
	  	// Set the parameters as an object
	  	var parameters = {
			placeholder: "House",
			url: 'auto/house',
			selector_id: house,
			value_id: road
		}
		// Pass it as a parameter to init_select
		// Initialize select2 on house
	  	init_select(parameters);
	  	$(this).valid(); // trigger validation on this element 
  	});



	//on territory change initialize sector
  	territory.change(function(){
	  	//clear selected value of road
	  	sector.val(null).trigger("change");
		// Set the parameters as an object
	  	var parameters = {
			placeholder: "Sector",
			url: 'auto/sector',
			selector_id: sector,
			value_id: territory
		}
		
		// Pass it as a parameter to init_select
		// Initialize select2 on sector
	  	init_select(parameters);
	  	$(this).valid(); // trigger validation on this element 
  	});
  	
  	//on house change initialize sector
  	house.change(function(){
	  	$(this).valid(); // trigger validation on this element
  	});
  	// ..... Modal scripts start here ..... //

  	// Add new territory
  	$('#submit_territory').click(function(event){    
  		event.preventDefault();
  		$.post(
  			"{{URL::to('/create_territory_process')}}",
  			$( "#territory_modal_form" ).serialize(),
  			function(data){
  				if(data.status == 'success'){
  					//append
  					$('#territory')
			        .append($("<option></option>")
	                    .attr("value", data.id)
				        .text(data.text));

			        // clear input fields
			        $('#territory_modal :input').val('');
			        //close the modal
			        $('#territory_modal').modal('toggle');
  				}
  			}
  		);
  	});
  	// Load territory data on sector modal
  	$('#sector_modal').on('show.bs.modal', function(){
	  	$.get( "{{URL::to('/auto/allterritory')}}", function( data ) {
	  		$('#sector_modal_territory').empty();
	  		$.each(data, function(index, element){

	  			$('#sector_modal_territory')
			        .append($("<option></option>")
	                	.attr("value", element.id)
	                    .text(element.text));
	  		});
		});

  	});

  	// Add new sector
  	$('#submit_sector').click(function(event){
  		event.preventDefault();
  		$.post(
  			"{{URL::to('/create_sector_process')}}",
  			$( "#sector_modal_form" ).serialize(),
  			function(data){
  				if(data.status == 'success'){
			        // clear input fields
			        $('#sector_modal :input').val('');
			        //close the modal
			        $('#sector_modal').modal('toggle');

  				}
  			}
  		);
  	});

  	// Load territory data
  	// Load sector data based on territory data using select2
  	$('#road_modal').on('show.bs.modal', function(){
  		$.get( "{{URL::to('/auto/allterritory')}}", function( data ) {
	  		$('#road_modal_territory').empty();
	  		$.each(data, function(index, element){

	  			$('#road_modal_territory')
			        .append($("<option></option>")
	                	.attr("value", element.id)
	                    .text(element.text));
	  		});
		});

		var road_modal_territory = $('#road_modal_territory'),
			road_modal_sector = $('#road_modal_sector');

		// Initialize sector select2 field when modal is opened
		// Set the parameters as an object
	  	var parameters = {
			placeholder: "Sector",
			url: 'auto/sector',
			selector_id: road_modal_sector,
			value_id: road_modal_territory
		}
		
		// Pass it as a parameter to init_select
		// Initialize select2 on sector
	  	init_select(parameters);

		//on territory change initialize sector
  		road_modal_territory.change(function(){
		  	//clear selected value of sector
		  	road_modal_sector.val(null).trigger("change");
			// Set the parameters as an object
		  	var parameters = {
				placeholder: "Sector",
				url: 'auto/sector',
				selector_id: road_modal_sector,
				value_id: road_modal_territory
			}
			
			// Pass it as a parameter to init_select
			// Initialize select2 on sector
		  	init_select(parameters);
  		});

  	});

  	// Add new road 
  	$('#submit_road').click(function(event){
  		event.preventDefault();
  		$.post(
  			"{{URL::to('/create_road_process')}}",
  			$( "#road_modal_form" ).serialize(),
  			function(data){
  				if(data.status == 'success'){
			        // clear input fields
			        $('#road_modal :input').val('');
			        // clear select2 field
			        $('#road_modal_sector').val(null).trigger("change");
			        //close the modal
			        $('#road_modal').modal('toggle');
			         
  				}
  			} 
  		);
  	});


  	// Load territory data
  	// Load sector data based on territory data using select2
  	// Load road data based on sector data using select2
  	$('#house_modal').on('show.bs.modal', function(){
  		$.get( "{{URL::to('/auto/allterritory')}}", function( data ) {
	  		$('#house_modal_territory').empty();
	  		$.each(data, function(index, element){

	  			$('#house_modal_territory')
			        .append($("<option></option>")
	                	.attr("value", element.id)
	                    .text(element.text));
	  		});
		});

		var house_modal_territory = $('#house_modal_territory'),
			house_modal_sector = $('#house_modal_sector'),
			house_modal_road = $('#house_modal_road');

		// Initialize sector select2 field when modal is opened
		// Set the parameters as an object
	  	var parameters = {
			placeholder: "Sector",
			url: 'auto/sector',
			selector_id: house_modal_sector,
			value_id: house_modal_territory
		}
			
		// Pass it as a parameter to init_select
		// Initialize select2 on sector
	  	init_select(parameters);

		//on territory change initialize sector
  		house_modal_territory.change(function(){
		  	//clear selected value of road
		  	house_modal_sector.val(null).trigger("change");
			// Set the parameters as an object
		  	var parameters = {
				placeholder: "Sector",
				url: 'auto/sector',
				selector_id: house_modal_sector,
				value_id: house_modal_territory
			}
			
			// Pass it as a parameter to init_select
			// Initialize select2 on sector
		  	init_select(parameters);
  		});

  		//on sector change initialize road
  		house_modal_sector.change(function(){
		  	//clear selected value of road
		  	house_modal_road.val(null).trigger("change");
			// Set the parameters as an object
		  	var parameters = {
				placeholder: "Road",
				url: 'auto/road',
				selector_id: house_modal_road,
				value_id: house_modal_sector
			}
			
			// Pass it as a parameter to init_select
			// Initialize select2 on road
		  	init_select(parameters);
  		});

  	});


  	// Add new house 
  	$('#submit_house').click(function(event){
  		event.preventDefault();
  		$.post(
  			"{{URL::to('/create_house_process')}}",
  			$( "#house_modal_form" ).serialize(),
  			function(data){
  				if(data.status == 'success'){
			        // clear input fields
			        $('#house_modal :input').val('');
			        // clear select2 fields
			        $('#house_modal_sector').val(null).trigger("change");
			        $('#house_modal_road').val(null).trigger("change");
			        //close the modal
			        $('#house_modal').modal('toggle');
			         
  				}
  			} 
  		);
  	});


  	/* ..... DELETE TERRITORY, SECTOR, ROAD & HOUSE ..... */
  	
	// Load territory data on delete territory modal  	
  	var delete_territory_modal = $('#delete_territory_modal'),
  		delete_territory_modal_territory = $('#delete_territory_modal_territory');

  	// Set the parameters as an object
  	var parameters = {
		url: '{{URL::to('/auto/territorywhichdoesnthavesector')}}',
		selector_id: delete_territory_modal,
		field_id: delete_territory_modal_territory
	}
	
	// Pass it as a parameter to init_select
	// load territory data
  	load_territory_data(parameters);

  	// Delete territory which does not have sectors
  	var submit_delete_territory = $('#submit_delete_territory'),
  		delete_territory_modal_form = $('#delete_territory_modal_form');

  	// Set the parameters as an object
  	var parameters = {
		url: '{{URL::to('/delete_territory_process')}}',
		selector_id: submit_delete_territory,
		form_id: delete_territory_modal_form,
		modal_id: delete_territory_modal,
		option_selector_id: 'delete_territory_modal_territory',
		territory_field : 'territory'
	}
	
	// Pass it as a parameter to init_select
	// delete territory
  	delete_territory_data(parameters);

  	// Load sector data on delete sector modal  	
  	var delete_sector_modal = $('#delete_sector_modal'),
  		delete_sector_modal_sector = $('#delete_sector_modal_sector');

  	// Set the parameters as an object
  	var parameters = {
		placeholder: "Sector",
		url: '{{URL::to('/auto/sectorwhichdoesnthaveroad')}}',
		selector_id_on: delete_sector_modal,
		selector_id_select: delete_sector_modal_sector
	}
	
	// Pass it as a parameter to init_select
	// load sector data
  	load_data(parameters);

  	// Delete sector which does not have roads
  	var submit_delete_sector = $('#submit_delete_sector'),
  		delete_sector_modal_form = $('#delete_sector_modal_form');

  	// Set the parameters as an object
  	var parameters = {
		url: '{{URL::to('/delete_sector_process')}}',
		selector_id: submit_delete_sector,
		form_id: delete_sector_modal_form,
		modal_id: delete_sector_modal,
		option_selector_id: 'delete_sector_modal_sector'
	}
	
	// Pass it as a parameter to init_select
	// delete sector
  	delete_data(parameters);

  	// Load road data on delete road modal  	
  	var delete_road_modal = $('#delete_road_modal'),
  		delete_road_modal_road = $('#delete_road_modal_road');

  	// Set the parameters as an object
  	var parameters = {
		placeholder: "road",
		url: '{{URL::to('/auto/roadwhichdoesnthavehouse')}}',
		selector_id_on: delete_road_modal,
		selector_id_select: delete_road_modal_road
	}
	
	// Pass it as a parameter to init_select
	// load road data
  	load_data(parameters);

  	// Delete road which does not have houses
  	var submit_delete_road = $('#submit_delete_road'),
  		delete_road_modal_form = $('#delete_road_modal_form');

  	// Set the parameters as an object
  	var parameters = {
		url: '{{URL::to('/delete_road_process')}}',
		selector_id: submit_delete_road,
		form_id: delete_road_modal_form,
		modal_id: delete_road_modal,
		option_selector_id: 'delete_road_modal_road'
	}
	
	// Pass it as a parameter to init_select
	// delete road
  	delete_data(parameters);


  	// Load house data on delete house modal  	
  	var delete_house_modal = $('#delete_house_modal'),
  		delete_house_modal_house = $('#delete_house_modal_house');

  	// Set the parameters as an object
  	var parameters = {
		placeholder: "house",
		url: '{{URL::to('/auto/housewhichdoesnthavecustomer')}}',
		selector_id_on: delete_house_modal,
		selector_id_select: delete_house_modal_house
	}
	
	// Pass it as a parameter to init_select
	// load house data
  	load_data(parameters);

  	// Delete houses which does not have customers
  	var submit_delete_house = $('#submit_delete_house'),
  		delete_house_modal_form = $('#delete_house_modal_form');

  	// Set the parameters as an object
  	var parameters = {
		url: '{{URL::to('/delete_house_process')}}',
		selector_id: submit_delete_house,
		form_id: delete_house_modal_form,
		modal_id: delete_house_modal,
		option_selector_id: 'delete_house_modal_house'
	}
	
	// Pass it as a parameter to init_select
	// delete house
  	delete_data(parameters);

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
        card.val(null).trigger("change");
        set_top_box.val(null).trigger("change");
    });
    
    //on distributor change initialize sub_distributor
    distributor.change(function(){
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
	        data: function(params) {
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
    card.change(function(){
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
            data: function(params) {
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
    set_top_box.change(function(){
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
	                <input type="text" class="form-control" name="name" id="customer_name" placeholder="Subscriber Name" value="{{ old('name') }}">
	            </div>
	            <div class="form-group">
	                <label>Phone*</label>
	                <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter phone" value="{{ old('phone') }}">
	            </div>
	            <div class="form-group">
	                <label>Email*</label>
	                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
	            </div>
	            <div class="form-group">
	            	<label>National Identity Card Number</label>
	            	<input type="text" class="form-control" name="nid" id="nid" placeholder="Enter nid number">
	            </div>
	            <div class="form-group">
	            	<label>Passport Number</label>
	            	<input type="text" class="form-control" name="passport" id="passport" placeholder="Enter passport number">
	            </div>
	            <div class="form-group">
					<label>Subscription Type*</label>
					<select class="form-control" name="subscription_types_id" id="subscription_type" style="width: 100%;">
						@foreach($subscription_types as $subscription_type)
			                <option value="{{$subscription_type->id}}">{{$subscription_type->name}}</option>
		                @endforeach
	                  
	        		</select>
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
	                <div class="input-group">
		                <select class="form-control" name="territory_id" id="territory">
			                @foreach($territory as $terr)
				                <option value="{{$terr->id}}">{{$terr->name}}</option>
			                @endforeach
		                </select>
		                <span class="input-group-btn">
	                        <button type="button" class="btn btn-block btn-info btn-flat" data-toggle="modal" data-target="#territory_modal">...</button>
	                    </span>
	                    <span class="input-group-btn">
	                        <button type="button" class="btn btn-block btn-danger btn-flat" data-toggle="modal" data-target="#delete_territory_modal">X</button>
	                    </span>
	                </div>
	            </div>
	            <div class="form-group">
					<label>Sector*</label>
					<div class="input-group">
						<select class="form-control select2" name="sectors_id" id="sector" style="width: 100%;">
		                  
	            		</select>
	            		<span class="input-group-btn">
	                        <button type="button" class="btn btn-block btn-info btn-flat" data-toggle="modal" data-target="#sector_modal">...</button>
	                    </span>
	                    <span class="input-group-btn">
	                        <button type="button" class="btn btn-block btn-danger btn-flat" data-toggle="modal" data-target="#delete_sector_modal">X</button>
	                    </span>
	        		</div>
				</div>
	            <div class="form-group">
					<label>Road*</label>
					<div class="input-group">
						<select class="form-control select2" name ="roads_id" id="road" style="width: 100%;">
		                  
	            		</select>
	            		<span class="input-group-btn">
	                        <button type="button" class="btn btn-block btn-info btn-flat" data-toggle="modal" data-target="#road_modal">...</button>
	                    </span>
	                    <span class="input-group-btn">
	                        <button type="button" class="btn btn-block btn-danger btn-flat" data-toggle="modal" data-target="#delete_road_modal">X</button>
	                    </span>
	        		</div>
				</div>
				<!-- /.form-group -->
				<div class="form-group">
					<label>House*</label>
					<div class="input-group">
						<select class="form-control select2" name ="houses_id" id="house" style="width: 100%;">
		                  
	            		</select>
	            		<span class="input-group-btn">
	                        <button type="button" class="btn btn-block btn-info btn-flat" data-toggle="modal" data-target="#house_modal">...</button>
	                    </span>
	                    <span class="input-group-btn">
	                        <button type="button" class="btn btn-block btn-danger btn-flat" data-toggle="modal" data-target="#delete_house_modal">X</button>
	                    </span>
	        		</div>
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
    <!-- Form for Add New Territory Modal  -->
    {!! Form::open(array('url' => 'create_territory_process', 'id' => 'territory_modal_form')) !!}
        <!-- Add New Territory Modal -->
        <div class="modal fade" id="territory_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New Territory</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
			                <label for="territory_modal">Territory Name</label>
			                <input type="text" class="form-control" name="territory_modal" id="territory_modal" placeholder="Territory Name">
	                	</div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submit_territory">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!--  Add New Territory Modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for Add New Territory Modal ends here -->

    <!-- Form for Delete Territory Modal  -->
    {!! Form::open(array('url' => 'delete_territory_process', 'id' => 'delete_territory_modal_form')) !!}
        <!-- Delete Territory Modal -->
        <div class="modal fade" id="delete_territory_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete Territory</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
			                <label>Territory</label>
			                <select class="form-control select2" name="delete_territory_modal_territory" id="delete_territory_modal_territory">
				                
			                </select>
	                	</div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" id="submit_delete_territory">Delete</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!--  Delete Territory Modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for Delete Territory Modal ends here -->

  	<!-- Form for Add New Sector Modal -->
  	{!! Form::open(array('url' => 'create_sector_process', 'id' => 'sector_modal_form')) !!}
        <!-- Add New Sector Modal -->
        <div class="modal fade" id="sector_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New Sector</h4>
                    </div>
                    <div class="modal-body">
                    	<div class="form-group">
			                <label>Territory</label>
			                <select class="form-control" name="sector_modal_territory" id="sector_modal_territory">

			                </select>
	                	</div>
	                	<div class="form-group">
			                <label for="sector_modal">Sector Name</label>
			                <input type="text" class="form-control" name="sector_modal" id="sector_modal" placeholder="Sector Name">
	                	</div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submit_sector">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!--  Add New Sector Modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for Add New Sector Modal ends here -->

    <!-- Form for Delete Sector Modal  -->
    {!! Form::open(array('url' => 'delete_sector_process', 'id' => 'delete_sector_modal_form')) !!}
        <!-- Delete Sector Modal -->
        <div class="modal fade" id="delete_sector_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete Sector</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
			                <label>Sector</label>
			                <select class="form-control select2" name="delete_sector_modal_sector" id="delete_sector_modal_sector">

			                </select>
	                	</div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" id="submit_delete_sector">Delete</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!--  Delete Sector Modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for Delete Sector Modal ends here -->

    <!-- Form for Add New Road Modal  -->
    {!! Form::open(array('url' => 'create_road_process', 'id' => 'road_modal_form')) !!}
        <!-- Add New Road Modal  -->
        <div class="modal fade" id="road_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New Road</h4>
                    </div>
                    <div class="modal-body">
                    	<div class="form-group">
			                <label>Territory</label>
			                <select class="form-control" name="road_modal_territory" id="road_modal_territory">
			                </select>
	                	</div>
	                	<div class="form-group">
							<label>Sector</label>
							<select class="form-control select2" name="road_modal_sector" id="road_modal_sector">

	                		</select>
						</div>
	                	<div class="form-group">
			                <label for="road_modal">Road Name</label>
			                <input type="text" class="form-control" name="road_modal" id="road_modal" placeholder="Road Name">
	                	</div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submit_road">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!--  Add New Road Modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for Add New Road Modal ends here -->

    <!-- Form for Delete Road Modal  -->
    {!! Form::open(array('url' => 'delete_road_process', 'id' => 'delete_road_modal_form')) !!}
        <!-- Delete Road Modal -->
        <div class="modal fade" id="delete_road_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete Road</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
			                <label>Road</label>
			                <select class="form-control select2" name="delete_road_modal_road" id="delete_road_modal_road">

			                </select>
	                	</div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" id="submit_delete_road">Delete</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!--  Delete Road Modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for Delete Road Modal ends here -->

    <!-- Form for Add New House Modal  -->
    {!! Form::open(array('url' => 'create_house_process', 'id' => 'house_modal_form')) !!}
        <!-- Add New House Modal -->
        <div class="modal fade" id="house_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New House</h4>
                    </div>
                    <div class="modal-body">
	                	<div class="form-group">
			                <label>Territory</label>
			                <select  class="form-control" name="house_modal_territory" id="house_modal_territory">
			                </select>
	                	</div>
	                	<div class="form-group">
							<label>Sector</label>
							<select  class="form-control select2" name="house_modal_sector" id="house_modal_sector">

	                		</select>
						</div>
						<div class="form-group">
							<label>Road</label>
							<select class="form-control select2" name="house_modal_road" id="house_modal_road">

	                		</select>
						</div>
	                	<div class="form-group">
			                <label for="house_modal">House Name</label>
			                <input type="text" class="form-control" name="house_modal" id="house_modal" placeholder="House Name">
	                	</div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submit_house">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!--  Add New House Modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for Add New House Modal ends here -->

    <!-- Form for Delete HouseRoad Modal  -->
    {!! Form::open(array('url' => 'delete_house_process', 'id' => 'delete_house_modal_form')) !!}
        <!-- Delete House Modal -->
        <div class="modal fade" id="delete_house_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete House</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
			                <label>House</label>
			                <select class="form-control select2" name="delete_house_modal_house" id="delete_house_modal_house">

			                </select>
	                	</div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" id="submit_delete_house">Delete</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!--  Delete House Modal ends here -->
    {!! Form::close() !!}
    <!-- /.  Form for Delete House Modal ends here -->
	<!-- All Modals end here -->
	

</section>
@endsection


