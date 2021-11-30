<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 5/20/2020
 * Time: 2:25 AM
 */

$employee = \App\Models\User::where('paynumber', $transaction->employee)->firstOrFail();
$done_by = \App\Models\User::where('name', $transaction->done_by)->firstOrFail();
?>
@extends('layouts.app')

@section('template_title')
    {{$transaction->trans_code}} info
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">{{$transaction->trans_code}} transaction details</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/transactions')}}">Transactions</a></li>
                        <li class="breadcrumb-item active">{{$transaction->id}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/transactions')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to transactions
                            </a>
                        </div>
                    </div>
&nbsp;&nbsp;&nbsp;&nbsp;
                    {{--<div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/transaction-pdf/'.$transaction->id)}}" type="button">
                                <i class="mdi mdi-printer mr-1"></i>Print
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
                            <div class="card-header">
                                Transaction Details
                            </div>
                            <br>
                            @if ($transaction->trans_code)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Transaction:
                                    </strong>

                                    {{$transaction->trans_code}}
                                </div>
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
                            @if ($transaction->voucher)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Voucher:
                                    </strong>

                                    {{$transaction->voucher}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($transaction->allocation)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Allocation:
                                    </strong>

                                    {{$transaction->allocation}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($transaction->ftype)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Fuel Type:
                                    </strong>

                                    {{$transaction->ftype}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($transaction->meter_start)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Meter Reading Start:
                                    </strong>

                                    {{$transaction->meter_start}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($transaction->meter_stop)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Meter Reading Stop:
                                    </strong>

                                    {{$transaction->meter_stop}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($transaction->quantity)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Quantity:
                                    </strong>

                                    {{$transaction->quantity}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif
                            <br>
                            @if ($transaction->amount)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Amount:
                                    </strong>

                                    {{$transaction->amount}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif
                            <br>
                            @if ($transaction->reg_num)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Issued Car Reg Number:
                                    </strong>

                                    {{$transaction->reg_num}}
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
                            @if ($transaction->created_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Recorded On:
                                    </strong>

                                    {{$transaction->created_at}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($transaction->updated_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Last Modified On:
                                    </strong>

                                    {{$transaction->updated_at}}
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
