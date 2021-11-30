<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 7/19/2020
 * Time: 10:16 PM
 */
?>
@extends('layouts.app')

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Clients</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/clients')}}">Clients</a></li>
                        <li class="breadcrumb-item active">Add Client</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/clients')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Clients
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
                            {!! Form::open(array('route' => 'clients.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('cli_name') ? ' has-error ' : '' }}">
                                {!! Form::label('cli_name', 'Client name', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('cli_name', NULL, array('id' => 'cli_name', 'class' => 'form-control', 'placeholder' => 'e.g ColZim')) !!}

                                    </div>
                                    @if ($errors->has('cli_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cli_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('cli_phone') ? ' has-error ' : '' }}">
                                {!! Form::label('cli_phone', 'Client Phone', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('cli_phone', NULL, array('id' => 'cli_phone', 'class' => 'form-control', 'placeholder' => 'e.g 0773 418 009')) !!}

                                    </div>
                                    @if ($errors->has('cli_phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cli_phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('cli_email') ? ' has-error ' : '' }}">
                                {!! Form::label('cli_email', 'Email', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::email('cli_email', NULL, array('id' => 'cli_email', 'class' => 'form-control', 'placeholder' => 'goodclient@client.co.zw')) !!}

                                    </div>
                                    @if ($errors->has('cli_email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cli_email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('cli_email_cc') ? ' has-error ' : '' }}">
                                {!! Form::label('cli_email_cc', 'Email CC', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::email('cli_email_cc', NULL, array('id' => 'cli_email_cc', 'class' => 'form-control', 'placeholder' => 'ccgoodclient@client.co.zw')) !!}

                                    </div>
                                    @if ($errors->has('cli_email_cc'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cli_email_cc') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('cli_contact') ? ' has-error ' : '' }}">
                                {!! Form::label('cli_contact', 'Contact Person', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('cli_contact', NULL, array('id' => 'cli_contact', 'class' => 'form-control', 'placeholder' => 'e.g. John Doe')) !!}

                                    </div>
                                    @if ($errors->has('cli_contact'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cli_contact') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {!! Form::button('Add Client', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
