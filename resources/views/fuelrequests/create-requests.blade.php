<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 6/30/2020
 * Time: 12:16 PM
 */
?>
@extends('layouts.app')

@section('template_title')
    Place Fuel Request
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
    <style>#hidden_div {
        display: none;
    }</style>
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Fuel Requests</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/frequests')}}">Fuel Requests</a></li>
                        <li class="breadcrumb-item active">Create Fuel Request</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    {{--<div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/frequests')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Fuel Requests
                            </a>
                        </div>
                    </div>--}}
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
                            {!! Form::open(array('route' => 'frequests.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="row">
                                @if($fsetting->petrol_available == 1)
                                <div class="col-lg-4">
                                    <div class="card border mb-0">
                                        <div class="card-body">
                                            <div class="text-center">
                                                <div class="icons-xl uim-icon-success my-4">
                                                    <i class="uim uim-check-circle"></i>
                                                </div>
                                                <h4 class="alert-heading font-size-20">Petrol Is Available</h4>
                                                <p class="text-muted">Great, your request will be processed.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    <div class="col-lg-4">
                                        <div class="card noti border mt-4 mt-lg-0 mb-0">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="icons-xl uim-icon-danger my-4">
                                                        <i class="uim uim-times-circle"></i>
                                                    </div>
                                                    <h4 class="alert-heading font-size-20">Petrol Is Unavailable</h4>
                                                    <p class="text-muted">We're going to stop you here. Our reserves are currently low, for Cash Sales.</p>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-4">
                                    <div class="card noti border mt-4 mt-lg-0 mb-0">
                                        <div class="card-body">

                                            <div class="text-center">
                                                <div class="icons-xl uim-icon-info my-4">
                                                    <i class="uim uim-exclamation-triangle"></i>
                                                </div>
                                                <p>Petrol: ${{$fsetting->petrol_price}}</p>
                                                <p>Diesel: ${{$fsetting->diesel_price}}</p>
                                                <p>Payment Method: {{$fsetting->pay_method}}</p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    @if($fsetting->diesel_available == 1)
                                        <div class="col-lg-4">
                                            <div class="card border mb-0">
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <div class="icons-xl uim-icon-success my-4">
                                                            <i class="uim uim-check-circle"></i>
                                                        </div>
                                                        <h4 class="alert-heading font-size-20">Diesel Is Available</h4>
                                                        <p class="text-muted">Great, your request will be processed.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-lg-4">
                                            <div class="card noti border mt-4 mt-lg-0 mb-0">
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <div class="icons-xl uim-icon-danger my-4">
                                                            <i class="uim uim-times-circle"></i>
                                                        </div>
                                                        <h4 class="alert-heading font-size-20">Diesel Is Unavailable</h4>
                                                        <p class="text-muted">We're going to stop you here. Our reserves are currently low, for Cash Sales.</p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                            </div>
                            <br>

                            @if(auth()->user()->allocation == 'Allocation')
                                @if($balance == null)
                                    <h4 class="header-title text-center text-danger">Please make sure that there are allocations created.</h4>
                                @else
                                <div class="card-body">
                                    <h4 class="header-title text-center text-info">You can currently apply for {{isset($balance->balance) ? $balance->balance : $balance->alloc_size}}L out of your {{$balance->alloc_size}}L for your {{date('F Y')}} allocation</h4>
                                    <h4 class="header-title text-center text-info">You can currently apply for {{isset($applicableCash) ? $applicableCash : ($balance->alloc_size)*0.5}}L out of your {{($balance->alloc_size)*0.5}}L in {{date('F Y')}} value for cash sales.</h4>
                                    <input type="hidden" name="alloc_bal" value="{{$balance->balance}}">
                                    <input type="hidden" name="applicable" value="{{$applicableCash}}">
                                    <input type="hidden" name="allowed" value="{{($balance->alloc_size)*0.5}}">
                                </div> <!-- end card-body-->
                                @endif
                            @else
                                <div class="card-body">
                                    <h4 class="header-title text-center text-info">You can currently apply for {{isset($balance->balance) ? $balance->balance : $balance->alloc_size}}L out of your {{$balance->alloc_size}}L for your {{date('F Y')}} Cashsale size</h4>
                                    <input type="hidden" name="alloc_bal" value="{{$balance->balance}}">
                                    <input type="hidden" name="applicable" value="{{$applicableCash}}">
                                    <input type="hidden" name="allowed" value="{{($balance->alloc_size)}}">
                                </div> <!-- end card-body-->
                            @endif

                            <div class="form-group has-feedback row {{ $errors->has('request_type') ? ' has-error ' : '' }}">
                                {!! Form::label('request_type', 'Request Type', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control" name="request_type" id="request_type"  onchange="showDiv('hidden_div', this)">
                                            <option value="Allocation">Allocation</option>
                                            <option value="Cash Sale">Cash Sale</option>
                                            <option value="Lockdown Duties">Lockdown Duties</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('request_type'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('request_type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if(\Illuminate\Support\Facades\Auth::user()->department == 'Diesel')
                                <div class="form-group has-feedback row {{ $errors->has('employee') ? ' has-error ' : '' }}">
                                    {!! Form::label('employee', 'Employee', array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <select class="custom-select form-control dynamic" name="employee" id="employee">
                                                <option value="">Select U$applicableCashser</option>
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
                            @else
                                <input type="hidden" name="employee" id="employee" value="{{Auth::user()->paynumber}}">
                                @endif

                            <div class="form-group has-feedback row {{ $errors->has('quantity') ? ' has-error ' : '' }}">
                                {!! Form::label('quantity', 'Quantity', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('quantity', NULL, array('id' => 'quantity', 'class' => 'form-control', 'placeholder' => 'e.g. Topup or Quantity in Litres')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="quantity">
                                                <i class="fa fa-fw fa-barcode" aria-hidden="true"></i>
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
                        <div id="hidden_div">
                            <div   class="form-group has-feedback row {{ $errors->has('amount') ? ' has-error ' : '' }}">
                                {!! Form::label('amount', 'Amount(zwl)', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('amount', NULL, array('id' => 'amount', 'class' => 'form-control', 'placeholder' => 'e.g. Amount of fuel to be paid for cash sales ')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="amount">
                                                <i class="fa fa-fw fa-barcode" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('amount'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('amount') }}</strong>
                                        </span>
                                    @endif
                                </div>
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

                            {!! Form::button('Request', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
        $("#request_type").select2({
            placeholder: 'Please select an option.',
            allowClear:true,
        });
    </script>

    <script type="text/javascript">
        $("#employee").select2({
            placeholder: 'Please select user.',
            allowClear:true,
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
<script>
    function showDiv(divId, element)
    {
        document.getElementById(divId).style.display = element.value == 'Cash Sale' ? 'block' : 'none';
    }

 </script>
    @endsection
