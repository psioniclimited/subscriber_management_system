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
{{-- Page script --}}
<script>
$(document).ready(function () {
    // initialize tooltipster on form input elements
    $('form input, select').tooltipster({// <-  USE THE PROPER SELECTOR FOR YOUR INPUTs
        trigger: 'custom', // default is 'hover' which is no good here
        onlyOne: false, // allow multiple tips to be open at a time
        position: 'right'  // display the tips to the right of the element
    });

    // initialize validate plugin on the form
    $('#add_sub_distributor_form').validate({
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
            fullname: {required: true, minlength: 4},
            uemail: {required: true, email: true},
            upassword: {required: true, minlength: 6},
            upassword_re: {required: true, equalTo: "#upassword"}
        },
        messages: {
            fullname: {required: "Please give fullname"},
            uemail: {required: "Insert email address"},
            upassword: {required: "Six digit password"},
            upassword_re: {required: "Re-enter same password"}
        }
    });

    // Distributors
    var distributor = $('#users_id');
    distributor.select2({
        placeholder: "Select a distributor",
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
    <!-- <div class="col-md-6"> -->
    <!-- Horizontal Form -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Create Sub Distributor</h3>
        </div><!-- /.box-header -->
        <!-- form starts here -->
        {!! Form::open(array('url' => 'create_sub_distributor_process', 'id' => 'add_sub_distributor_form', 'class' => 'form-horizontal')) !!}
        <div class="box-body">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="fullname">Fullname*</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter fullname">
                </div>
                <div class="form-group">
                    <label for="uemail">Email*</label>
                    <input type="email" class="form-control" id="uemail" name="uemail" placeholder="Enter email">
                </div>
                @if(Entrust::hasRole('admin'))
                    <div class="form-group">
                        <label>Distributor*</label>
                        <select class="form-control select2" name="users_id" id="users_id"></select>
                    </div>
                @endif
            </div><!-- /.col -->
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Territory</label>
                    <select name="territory" id="territory" class="form-control">
                        @foreach($territory as $terr)
                            <option value="{{$terr->id}}">{{$terr->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="upassword">Password*</label>
                    <input type="password" class="form-control" id="upassword" name="upassword" placeholder="Enter password">
                </div>
                <div class="form-group">
                    <label for="upassword_re">Confirm Password*</label>
                    <input type="password" class="form-control" id="upassword_re" name="upassword_re" placeholder="Enter password again">
                </div>
            </div><!-- /.col -->
        </div><!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">Submit</button>
        </div><!-- /.box-footer -->
        {!! Form::close() !!}
        <!-- /.form ends here -->
    </div><!-- /.box -->
    <!-- </div> -->
</section><!-- /.content -->

@endsection

