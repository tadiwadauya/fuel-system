<?php
/**
 *Created by PhpStorm for WhelsonFuelSystem
 *User: Vincent Guyo
 *Date: 31/7/2020
 *Time: 3:23 PM
 */

$employee = \App\Models\User::where('paynumber', $stockIssue->employee)->firstOrFail();
$done_by = \App\Models\User::where('name', $stockIssue->done_by)->firstOrFail();
?>
@extends('layouts.app')

@section('template_title')
    {{$stockIssue->trans_code}} info
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">{{$stockIssue->trans_code}} stock issue details</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/stockissues')}}">Stock Issues</a></li>
                        <li class="breadcrumb-item active">{{$stockIssue->id}}</li>
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
                            <div class="card-header">
                                Stock Issue Details
                            </div>
                            <br>
                            @if ($stockIssue->trans_code)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Transaction:
                                    </strong>

                                    {{$stockIssue->trans_code}}
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
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($stockIssue->voucher)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Voucher:
                                    </strong>

                                    {{$stockIssue->voucher}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($stockIssue->narration)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Narration:
                                    </strong>

                                    {{$stockIssue->narration}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($stockIssue->ftype)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Fuel Type:
                                    </strong>

                                    {{$stockIssue->ftype}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($stockIssue->meter_start)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Meter Reading Start:
                                    </strong>

                                    {{$stockIssue->meter_start}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($stockIssue->meter_stop)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Meter Reading Stop:
                                    </strong>

                                    {{$stockIssue->meter_stop}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($stockIssue->quantity)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Quantity:
                                    </strong>

                                    {{$stockIssue->quantity}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($stockIssue->reg_num)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Issued Car Reg Number:
                                    </strong>

                                    {{$stockIssue->reg_num}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($done_by)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Issued by:
                                    </strong>

                                    {{$done_by->first_name}} {{$done_by->last_name}} - {{$done_by->paynumber}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($stockIssue->created_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Recorded On:
                                    </strong>

                                    {{$stockIssue->created_at}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($stockIssue->updated_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Last Modified On:
                                    </strong>

                                    {{$stockIssue->updated_at}}
                                </div>
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


