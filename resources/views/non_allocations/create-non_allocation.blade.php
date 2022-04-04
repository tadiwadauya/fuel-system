<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 4/28/2020
 * Time: 8:03 AM
 */
?>
@extends('layouts.app')

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Non Allocations</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/allocations')}}">Non Allocations</a></li>
                        <li class="breadcrumb-item active">Create Non Allocation</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/non_allocations')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Non allocations
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
                            {!! Form::open(array('route' => 'allocations.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('paynumber') ? ' has-error ' : '' }}">
                                {!! Form::label('paynumber', 'Employee', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="custom-select form-control dynamic" name="paynumber" id="paynumber">
                                            <option value="">Select User</option>
                                            @if ($users)
                                                @foreach($users as $user)
                                                    <option value="{{ $user->paynumber }}" data-price="{{$user->id}}">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->paynumber }})- {{ $user->department }} Current allocation size:60</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @if ($errors->has('paynumber'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('paynumber') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" name="user_id" id="user_id" value="NULL">

                            <div class="form-group has-feedback row {{ $errors->has('allocation') ? ' has-error ' : '' }}">
                                {!! Form::label('allocation', 'Allocation', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('allocation', NULL, array('id' => 'allocation', 'class' => 'form-control', 'placeholder' => 'e.g. {paynumber}{Month}{Year} like 25January2020')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="allocation">
                                                <i class="fa fa-fw fa-barcode" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('allocation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('allocation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('alloc_size') ? ' has-error ' : '' }}">
                                {!! Form::label('alloc_size', 'Allocation Size', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('alloc_size', NULL, array('id' => 'alloc_size', 'class' => 'form-control','pattern'=>"^\d{1,3}*(\.\d+)?$", 'value'=>"60", 'data-type'=>"currency", 'placeholder' => '60')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="alloc_size">
                                                <i class="fa fa-fw fa-gas-pump" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('alloc_size'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('alloc_size') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Create Allocation', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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

    <script type="text/javascript">
        $("#paynumber").select2({
            placeholder: 'Please select a user',
            allowClear:true,
        }).on('change',function(){
            var price = $(this).children('option:selected').data('price');
            $('#user_id').val(price);
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
