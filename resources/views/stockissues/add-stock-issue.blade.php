<?php
/**
 *Created by PhpStorm for WhelsonFuelSystem
 *User: Vincent Guyo
 *Date: 31/7/2020
 *Time: 1:57 PM
 */

?>
@extends('layouts.app')

@section('template_title')
    Add Stock Issue
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
    <!-- datepicker -->
    <link href="{{asset('assets/libs/air-datepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Stock Issues</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/stockissues')}}">Stock Issues</a></li>
                        <li class="breadcrumb-item active">Add Stock Issue</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/stockissues')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to stock issues
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- end page title end breadcrumb -->
    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            {!! Form::open(array('route' => 'stockissues.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('created_at') ? ' has-error ' : '' }}">
                                {!! Form::label('created_at', 'Date Of Issue', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('created_at', NULL, array('id' => 'created_at', 'class' => 'form-control datepicker-here', 'data-timepicker'=>'true','data-language'=>'en', 'placeholder' => 'e.g. 2020-01-31 00:00:01')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="created_at">
                                                <i class="fa fa-fw fa-barcode" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('created_at'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('created_at') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('employee') ? ' has-error ' : '' }}">
                                {!! Form::label('employee', 'Employee', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="custom-select form-control dynamic" name="employee" id="employee">
                                            <option value="">Select User</option>
                                            @if ($users)
                                                @foreach($users as $user)
                                                    <option value="{{ $user->paynumber }}" >{{ $user->first_name }} {{ $user->last_name }} - {{ $user->department }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @if ($errors->has('employee'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('employee') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('voucher') ? ' has-error ' : '' }}">
                                {!! Form::label('voucher', 'Voucher', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('voucher', NULL, array('id' => 'voucher', 'class' => 'form-control', 'placeholder' => 'e.g. 619619')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="voucher">
                                                <i class="fa fa-fw fa-barcode" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('voucher'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('voucher') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('narration') ? ' has-error ' : '' }}">
                                {!! Form::label('narration', 'Narration', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('narration', NULL, array('id' => 'narration', 'class' => 'form-control', 'placeholder' => 'e.g. Lockdown duties')) !!}

                                    </div>
                                    @if ($errors->has('narration'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('narration') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('ftype') ? ' has-error ' : '' }}">
                                {!! Form::label('ftype', 'Fuel Type', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control" name="ftype" id="ftype">
                                            <option value="Petrol">Petrol</option>
                                            <option value="Diesel">Diesel</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('ftype'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('ftype') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('meter_start') ? ' has-error ' : '' }}">
                                {!! Form::label('meter_start', 'Meter start', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('meter_start', NULL, array('id' => 'meter_start', 'class' => 'form-control','pattern'=>"^\d{1,3}*(\.\d+)?$", 'value'=>"", 'data-type'=>"currency", 'placeholder' => 'e.g. 098745632')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="meter_start">
                                                <i class="fa fa-fw fa-gas-pump" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('meter_start'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('meter_start') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('meter_stop') ? ' has-error ' : '' }}">
                                {!! Form::label('meter_stop', 'Meter stop', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('meter_stop', NULL, array('id' => 'meter_stop', 'class' => 'form-control','pattern'=>"^\d{1,3}*(\.\d+)?$", 'value'=>"", 'data-type'=>"currency", 'placeholder' => 'e.g. 087654321')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="meter_stop">
                                                <i class="fa fa-fw fa-gas-pump" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('meter_stop'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('meter_stop') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('quantity') ? ' has-error ' : '' }}">
                                {!! Form::label('quantity', 'Quantity', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('quantity', NULL, array('id' => 'quantity', 'class' => 'form-control','pattern'=>"^\d{1,3}*(\.\d+)?$", 'value'=>"", 'data-type'=>"currency", 'placeholder' => 'Amount in Litres e.g. 80')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="quantity">
                                                <i class="fa fa-fw fa-gas-pump" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('quantity'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('quantity') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('reg_num') ? ' has-error ' : '' }}">
                                {!! Form::label('reg_num', 'Car Reg Number', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('reg_num', NULL, array('id' => 'reg_num', 'class' => 'form-control', 'placeholder' => 'e.g. ACA-4029')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="reg_num">
                                                <i class="fa fa-fw fa-barcode" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('reg_num'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('reg_num') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" name="done_by" id="done_by" value="{{Auth::user()->name}}">

                            {!! Form::button('Record', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_scripts')

    <script src="{{ asset('js/select2.min.js')}}"></script>
    <script src="{{asset('assets/libs/air-datepicker/js/datepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/air-datepicker/js/i18n/datepicker.en.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>

    <script>
        $("#meter_stop").keyup(function(){
            $("#quantity").html('');
            var n1 = $("#meter_start").val();
            var n2 = $("#meter_stop").val();
            var ans = n2 - n1;
            $("#quantity").val(ans);

        });

        $("#meter_start").keyup(function(){
            $("#quantity").html('');
            var n1 = $("#meter_start").val();
            var n2 = $("#meter_stop").val();
            var ans = n2 - n1;
            $("#quantity").val(ans);
        });
    </script>

    <script type="text/javascript">
        $("#ftype").select2({
            placeholder: 'Please select fuel type.',
            allowClear:true,
        });
    </script>

    <script type="text/javascript">
        $("#allocation").select2({
            placeholder: 'Please select an allocation.',
            allowClear:true,
        });
    </script>

    <script type="text/javascript">
        $('#employee').select2({
            placeholder: 'Please select a user.',
            allowClear:true,
        }).change(function(){
            var paynumber = $(this).val();
            var _token = $("input[name='_token']").val();
            if(paynumber){
                $.ajax({
                    type:"get",
                    url:'{{url('/getAllocations')}}/'+paynumber,
                    _token: _token ,
                    success:function(res) {
                        if(res) {
                            $("#allocation").empty();
                            $.each(res,function(key, value){
                                $("#allocation").append('<option value="'+value+'">'+value+'</option>');
                            });
                        }
                    }
                });
            }
        });

    </script>


    <script>

        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });


        function formatNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "")
        }


        function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.

            // get input value
            var input_val = input.val();

            // don't validate empty input
            if (input_val === "") { return; }

            // original length
            var original_len = input_val.length;

            // initial caret position
            var caret_pos = input.prop("selectionStart");

            // check for decimal
            if (input_val.indexOf(".") >= 0) {

                // get position of first decimal
                // this prevents multiple decimals from
                // being entered
                var decimal_pos = input_val.indexOf(".");

                // split number by decimal point
                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                // add commas to left side of number
                left_side = formatNumber(left_side);

                // validate right side
                right_side = formatNumber(right_side);

                // On blur make sure 2 numbers after decimal
                if (blur === "blur") {
                    right_side += "00";
                }

                // Limit decimal to only 2 digits
                right_side = right_side.substring(0, 2);

                // join number by .
                input_val = left_side + "." + right_side;

            } else {
                // no decimal entered
                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                //input_val = input_val;

                // final formatting
                if (blur === "blur") {
                    input_val += ".00";
                }
            }

            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    </script>
@endsection
