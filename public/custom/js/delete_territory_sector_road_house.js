var load_territory_data = function(parameters){
	parameters.selector_id.on('show.bs.modal', function(){
	  	$.get(parameters.url, function( data ) {
	  		parameters.field_id.empty();
	  		$.each(data, function(index, element){
	  			parameters.field_id
			        .append($("<option></option>")
	                	.attr("value", element.id)
	                    .text(element.text));
	  		});
		});

  	});
}

var delete_territory_data = function(parameters){
	parameters.selector_id.click(function(event){
  		var form_data = parameters.form_id.serializeArray();
	        event.preventDefault();
	        $.ajax({
	            cache: false,
	            type: 'POST',
	            url: parameters.url,
	            data: {
	                form_data
	            },
	            success: function(data){
	                if(data.status == 'success'){
	                	parameters.modal_id.modal('toggle');
	                	var selector_modal = "#"+parameters.option_selector_id+" option[value='" + data.id + "']";
	                	var selector = "#"+parameters.territory_field+" option[value='" + data.id + "']";
	                	$(selector_modal).remove();
	                	$(selector).remove();
	                }
	            }

	        });
  	});
}

var load_data = function(parameters){
	parameters.selector_id_on.on('show.bs.modal', function(){
	    var placeholder_text = 'Select a ';
	    parameters.selector_id_select.select2({
	        allowClear: true,
	        placeholder: placeholder_text + parameters.placeholder,
	        ajax: {
	            dataType: 'json',
	            url: parameters.url,
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

  	});
}

var delete_data = function(parameters){
	parameters.selector_id.click(function(event){
  		var form_data = parameters.form_id.serializeArray();
	        event.preventDefault();
	        $.ajax({
	            cache: false,
	            type: 'POST',
	            url: parameters.url,
	            data: {
	                form_data
	            },
	            success: function(data){
	                if(data.status == 'success'){
	                	parameters.modal_id.modal('toggle');
	                	var selector = "#"+parameters.option_selector_id+" option[value='" + data.id + "']";
	                	$(selector).remove();
	                }
	            }

	        });
  	});
}



