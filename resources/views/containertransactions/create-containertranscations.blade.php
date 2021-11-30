<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 7/24/2020
 * Time: 7:09 PM
 */
?>
@extends('layouts.app')

@section('template_title')
    Adding ContainerTransaction
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
                    <h4 class="page-title mb-1">ContainerTransaction</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/containertransactions')}}">ContainerTransactionInvoices</a></li>
                        <li class="breadcrumb-item active">Add ContainerTransaction</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/containertransactions')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>ContainerTransactions
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
                            {!! Form::open(array('route' => 'containertransactions.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('batchname') ? ' has-error ' : '' }}">
                                {!! Form::label('batchname', 'Batch', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="custom-select form-control dynamic" name="batchname" id="batchname">
                                            <option value="">Select Batch</option>
                                            @if ($batches)
                                                @foreach($batches as $batch)
                                                    <option value="{{ $batch->code }}" >{{ $batch->code }} - {{ $batch->remaining }}L left </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @if ($errors->has('batchname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('batchname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('container') ? ' has-error ' : '' }}">
                                {!! Form::label('container', 'Container', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="custom-select form-control dynamic" name="container" id="container">
                                            <option value="">Select Container</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('container'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('container') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('client') ? ' has-error ' : '' }}">
                                {!! Form::label('client', 'Client', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="custom-select form-control dynamic" name="client" id="client">
                                            <option value="">Select Client</option>
                                            @if ($clients)
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}" >{{ $client->client }} </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @if ($errors->has('client'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('client') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('conrate') ? ' has-error ' : '' }}">
                                {!! Form::label('conrate', 'Container Rate', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('conrate', NULL, array('id' => 'conrate', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'value'=>"", 'data-type'=>"currency", 'placeholder' => 'e.g 1.31')) !!}

                                    </div>
                                    @if ($errors->has('conrate'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('conrate') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group has-feedback row {{ $errors->has('date_input') ? ' has-error ' : '' }}">
                                {!! Form::label('date_input', 'Date Of Transaction', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('date_input', NULL, array('id' => 'date_input', 'class' => 'form-control datepicker-here', 'data-timepicker'=>'true','data-language'=>'en', 'placeholder' => 'e.g. 2020-01-31 00:00:01')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="date_input">
                                                <i class="fa fa-fw fa-barcode" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('date_inputt'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('date_input') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group has-feedback row {{ $errors->has('bal_before') ? ' has-error ' : '' }}">
                                {!! Form::label('bal_before, 'Container Balance before', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('bal_before', NULL, array('id' => 'bal_before', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'value'=>"", 'data-type'=>"currency", 'placeholder' => 'e.g 1.31')) !!}

                                    </div>
                                    @if ($errors->has('bal_before'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bal_before') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>




                            <div class="form-group has-feedback row {{ $errors->has('quantity') ? ' has-error ' : '' }}">
                                {!! Form::label('quantity', 'Quantity', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('quantity', NULL, array('id' => 'quantity', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'value'=>"", 'data-type'=>"currency", 'placeholder' => 'Amount in Litres e.g. 80')) !!}

                                    </div>
                                    @if ($errors->has('quantity'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('quantity') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('total') ? ' has-error ' : '' }}">
                                {!! Form::label('total', 'Total', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('total', NULL, array('id' => 'total', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'value'=>"", 'data-type'=>"currency", 'placeholder' => 'Total in Litres e.g. 80')) !!}

                                    </div>
                                    @if ($errors->has('total'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('total') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" name="done_by" value="{{auth()->user()->name}}">

                            {!! Form::button('Add Invoice', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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



    <script type="text/javascript">
        $("#container").select2({
            placeholder: 'Please select a container.',
            allowClear:true,
        });
    </script>

    <script type="text/javascript">
        $('#client').select2({
            placeholder: 'Please select a client.',
            allowClear:true,
        }).change(function(){
            var conid = $(this).val();
            var _token = $("input[name='_token']").val();
            if(conid){
                $.ajax({
                    type:"get",
                    url:'{{url('/getContainers')}}/'+conid,
                    _token: _token ,
                    success:function(res) {
                        if(res) {
                            $("#container").empty();
                            $.each(res,function(key, value){
                                $("#container").append('<option value="'+value.conname+'">'+value.conname+' - '+value.conbalance+'L Left</option>');
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
