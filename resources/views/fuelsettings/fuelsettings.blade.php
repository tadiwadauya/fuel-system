<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 6/30/2020
 * Time: 3:24 PM
 */
?>
@extends('layouts.app')

@section('template_title')
    Fuel Settings
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Global Fuel Settings</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Fuel Settings</a></li>
                        <li class="breadcrumb-item active">Allocations Fuel Settings</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        {{--<div class="dropdown">
                            <button class="btn btn-light btn-rounded dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-more"></i> Action
                            </button>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                <a class="dropdown-item" href="#">Create New User</a>
                                <a class="dropdown-item" href="#">Show Deleted Users</a>
                            </div>
                        </div>--}}
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
                            <div class="card-body">
                                <h4 class="header-title">Configure the Global System Settings Used for Fuel Management.</h4> <br>

                                {!! Form::open(array('route' => ['fsetting.update',$fsetting->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation', 'novalidate')) !!}

                                {!! csrf_field() !!}

                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="petrol_available">Is <strong>Petrol</strong> Available for Cash Sales?</label><br>
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-outline-primary">
                                                <input type="radio" name="petrol_available" id="petrol_available" value="1" {{ ($fsetting->petrol_available == 1)? "checked" : "" }}> Make Petrol Available
                                            </label>
                                            <label class="btn btn-outline-primary">
                                                <input type="radio" name="petrol_available" id="petrol_available" value="0" {{ ($fsetting->petrol_available == 0)? "checked" : "" }}> Petrol is not available
                                            </label>
                                        </div>
                                        @if ($errors->has('petrol_available'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('petrol_available') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="diesel_available">Is <strong>Diesel</strong> Available for Cash Sales?</label><br>
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-outline-primary">
                                                <input type="radio" name="diesel_available" id="diesel_available" value="1" {{ ($fsetting->diesel_available =="1")? "checked" : "" }}> Make Diesel Available
                                            </label>
                                            <label class="btn btn-outline-primary">
                                                <input type="radio" name="diesel_available" id="diesel_available" value="0" {{ ($fsetting->diesel_available =="0")? "checked" : "" }}> Diesel is not available
                                            </label>
                                        </div>
                                        @if ($errors->has('diesel_available'))
                                            <span class="help-block text-danger">
                                            <strong>{{ $errors->first('diesel_available') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <br>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="petrol_price">Petrol Price*</label>
                                            <input type="text" class="form-control" id="petrol_price" name="petrol_price" placeholder="31.31" value="{{$fsetting->petrol_price}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" required>
                                            @if ($errors->has('petrol_price'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('petrol_price') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="diesel_price">Diesel Price*</label>
                                            <input type="text" class="form-control" id="diesel_price" name="diesel_price" placeholder="31.31" value="{{$fsetting->diesel_price}}" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency" required>
                                            @if ($errors->has('diesel_price'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('diesel_price') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="oil_price">Oil Price</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="oil_price" name="oil_price" placeholder="31.31" aria-describedby="inputGroupPrepend" pattern="^\d{1,3}*(\.\d+)?$" data-type="currency">
                                                @if ($errors->has('oil_price'))
                                                    <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('oil_price') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label>Payment Method*</label>
                                            <select class="custom-select" name="pay_method" required>
                                                <option value="{{$fsetting->pay_method}}">{{$fsetting->pay_method}}</option>
                                                <option value="EcoCash">EcoCash</option>
                                                <option value="Swipe">Swipe</option>
                                                <option value="Cash">Cash</option>
                                            </select>
                                            @if ($errors->has('pay_method'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('pay_method') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="last_changed_by">Last Modified By</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="last_changed_by" placeholder="Username" name="last_changed_by" value="{{auth()->user()->name}}" aria-describedby="inputGroupPrepend" readonly>

                                            </div>
                                        </div>
                                    </div>


                                {!! Form::button('Update Settings', array('class' => 'btn btn-primary float-right','type' => 'submit' )) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
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
