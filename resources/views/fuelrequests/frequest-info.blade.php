<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 7/1/2020
 * Time: 3:20 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    {{$employee->first_name}} {{$employee->last_name}} request info
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">{{$employee->first_name}} {{$employee->last_name}} request details</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/frequests')}}">Fuel Requests</a></li>
                        <li class="breadcrumb-item active">{{$frequest->id}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a href="{{ url('/frequest-pdf/'.$frequest->id) }}" class="btn btn-light btn-sm float-right" data-toggle="tooltip" data-placement="left" title="After downloading, submit to Human Resources">
                                <i class="fa fa-fw fa-download" aria-hidden="true"></i>
                                Download Form
                            </a>
                            <a class="btn btn-light btn-rounded" href="{{url('/frequests')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to fuel requests
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
                            <div class="card-header">
                                Request Details
                            </div>
                            <br>
                            @if ($frequest->request_type)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Request Type:
                                    </strong>

                                    {{$frequest->request_type}}
                                </div>
                                <br>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($employee)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Employee:
                                    </strong>

                                    {{$employee->first_name}} {{$employee->last_name}} - {{$employee->paynumber}}
                                </div>
                                <br>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($frequest->quantity)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Quantity:
                                    </strong>

                                    {{$frequest->quantity}}
                                </div>
                                <br>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($frequest->quantity)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Amount:
                                    </strong>

                                    {{$frequest->amount}}
                                </div>
                                <br>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($frequest->ftype)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Fuel Type:
                                    </strong>

                                    {{$frequest->ftype}}
                                </div>
                                <br>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>

                            <div class="col-sm-5 col-6 text-larger">
                                <strong>
                                    Status:
                                </strong>

                                @if($frequest->status == 1) Approved @else Pending Approval @endif
                            </div>
                            <br>
                            <div class="clearfix"></div>
                            <div class="border-bottom"></div>

                            @if ($frequest->approved_by)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Approved By:
                                    </strong>

                                    {{$frequest->approved_by}}
                                </div>
                                <br>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($frequest->approved_when)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Approved When:
                                    </strong>

                                    {{$frequest->approved_when}}
                                </div>
                                <br>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($frequest->created_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Requested On:
                                    </strong>

                                    {{$frequest->created_at}}
                                </div>
                                <br>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($frequest->updated_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Last Modified On:
                                    </strong>

                                    {{$frequest->updated_at}}
                                </div>
                                <br>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

