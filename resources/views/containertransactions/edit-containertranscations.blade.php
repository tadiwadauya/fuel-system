<?php
/**
 *Created by PhpStorm for WhelsonFuelSystem
 *User: Vincent Guyo
 *Date: 30/7/2020
 *Time: 9:52 PM
 */

?>
@extends('layouts.app')

@section('template_title')
    Modifying Invoice
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
                    <h4 class="page-title mb-1">ContainerTransactions</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/containertransactions')}}">containertransactions</a></li>
                        <li class="breadcrumb-item active">Edit Invoice</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/containertransactions')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to ContainerTransaction
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
                            {!! Form::open(array('route' => ['containertransactions.update', $invoice->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has(date_input') ? ' has-error ' : '' }}">
                                {!! Form::label(date_input', 'Date Of Transaction', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text(date_input', $invoice->created_at, array('id' => date_input', 'class' => 'form-control', 'data-timepicker'=>'true','data-language'=>'en', 'readonly')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="created_at">
                                                <i class="fa fa-fw fa-barcode" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has(date_input'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first(date_input') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('client') ? ' has-error ' : '' }}">
                                {!! Form::label('client', 'Client', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('client', $client->cli_name, array('id' => 'client', 'class' => 'form-control','data-language'=>'en', 'readonly')) !!}

                                    </div>
                                    @if ($errors->has('client'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('client') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('container') ? ' has-error ' : '' }}">
                                {!! Form::label('container', 'Container', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('container', $invoice->container, array('id' => 'container', 'class' => 'form-control', 'data-language'=>'en', 'readonly')) !!}

                                    </div>
                                    @if ($errors->has('container'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('container') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>



                            <div class="form-group has-feedback row {{ $errors->has('quantity') ? ' has-error ' : '' }}">
                                {!! Form::label('quantity', 'Quantity', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('quantity', $invoice->quantity, array('id' => 'quantity', 'class' => 'form-control','pattern'=>'^\d{1,3}*(\.\d+)?$', 'value'=>"", 'data-type'=>"currency", 'placeholder' => 'Amount in Litres e.g. 80', 'readonly')) !!}

                                    </div>
                                    @if ($errors->has('quantity'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('quantity') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>




                            <div class="form-group has-feedback row {{ $errors->has('done_by') ? ' has-error ' : '' }}">
                                {!! Form::label('done_by', 'Added By', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('done_by', $invoice->done_by, array('id' => 'done_by', 'class' => 'form-control', 'placeholder' => 'e.g. vguyo', 'readonly')) !!}

                                    </div>
                                    @if ($errors->has('done_by'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('done_by') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Update Invoice', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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

@endsection
