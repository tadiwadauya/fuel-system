<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 5/21/2020
 * Time: 9:37 AM
 */

$employee = \App\Models\User::where('paynumber', $cashSale->employee)->firstOrFail();
$done_by = \App\Models\User::where('name', $cashSale->done_by)->firstOrFail();
?>
@extends('layouts.app')

@section('template_title')
    {{$cashSale->trans_code}} info
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">{{$cashSale->trans_code}} cash sale details</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/cashsales')}}">Cash Sales</a></li>
                        <li class="breadcrumb-item active">{{$cashSale->id}}</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/cashsales')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to cash sales
                            </a>
                        </div>
                    </div>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    {{--<div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/cashsale-pdf/'.$cashSale->id)}}" type="button">
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
                                Cash Sale Details
                            </div>
                            <br>
                            @if ($cashSale->trans_code)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Transaction:
                                    </strong>

                                    {{$cashSale->trans_code}}
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
                            @if ($cashSale->voucher)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Voucher:
                                    </strong>

                                    {{$cashSale->voucher}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($cashSale->allocation)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Allocation:
                                    </strong>

                                    {{$cashSale->allocation}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($cashSale->ftype)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Fuel Type:
                                    </strong>

                                    {{$cashSale->ftype}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($cashSale->meter_start)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Meter Reading Start:
                                    </strong>

                                    {{$cashSale->meter_start}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($cashSale->meter_stop)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Meter Reading Stop:
                                    </strong>

                                    {{$cashSale->meter_stop}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($cashSale->quantity)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Quantity:
                                    </strong>

                                    {{$cashSale->quantity}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($cashSale->invoice_number)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Invoice No.:
                                    </strong>

                                    {{$cashSale->invoice_number}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif
                            <br>
                            @if ($cashSale->amount)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Amount:
                                    </strong>

                                    {{$cashSale->amount}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($cashSale->reg_num)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Issued Car Reg Number:
                                    </strong>

                                    {{$cashSale->reg_num}}
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
                            @if ($cashSale->created_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Recorded On:
                                    </strong>

                                    {{$cashSale->created_at}}
                                </div>
                                <div class="clearfix"></div>
                                <div class="border-bottom"></div>

                            @endif

                            <br>
                            @if ($cashSale->updated_at)

                                <div class="col-sm-5 col-6 text-larger">
                                    <strong>
                                        Last Modified On:
                                    </strong>

                                    {{$cashSale->updated_at}}
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

