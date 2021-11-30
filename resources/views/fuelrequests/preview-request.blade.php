<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 7/2/2020
 * Time: 1:10 AM
 */
?>
@extends('layouts.app')

@section('template_title')
    Preview Fuel Request
@endsection


@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Fuel Requests</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/frequests')}}">Fuel Requests</a></li>
                        <li class="breadcrumb-item active">Reviewing Fuel Request for {{$user->first_name}} {{$user->last_name}} ({{$user->department}} Department)</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/manage-requests')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Manage Fuel Requests
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
                            <h4 class="header-title">Reviewing Fuel Request</h4>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="validationCustom01">Employee</label>
                                        <input type="text" class="form-control" id="validationCustom01" placeholder="e.g. Employee name" value="{{$user->first_name}} {{$user->last_name}}" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="validationCustomUsername">Allocation Size</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="validationCustomUsername" placeholder="e.g. 80L" value="@if($user->allocation == 'Non-allocation')Non-Allocation @else {{$user->alloc_size}}@endif" aria-describedby="inputGroupPrepend" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="validationCustom02">Request Type</label>
                                        <input type="text" class="form-control" id="validationCustom02" placeholder="e.g. Allocation or Cash Sale" value="{{$frequest->request_type}}" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="validationCustomUsername">Fuel Requested</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="validationCustomUsername" placeholder="e.g. Fuel type and quantity" value="{{$frequest->quantity}} of {{$frequest->ftype}}" aria-describedby="inputGroupPrepend" required>
                                        </div>
                                    </div>
                                </div>
                            <a class="btn btn-success" href="{{ URL::to('/frequests/approve/' . $frequest->id) }}" >
                                Approve Request
                            </a>

                            <a class="btn btn-outline-danger float-right" href="{{ URL::to('/frequests/reject/' . $frequest->id) }}" >
                                Reject Request
                            </a>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Recent Transactions</h4>
                            <p class="card-title-desc">Last 10 Fuel Allocations issued to {{$user->first_name}}.</p>

                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Car Reg</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($previousTrans as $transaction)
                                        <tr>
                                            <td>{{$transaction->created_at}}</td>
                                            <td>{{$transaction->ftype}}</td>
                                            <td>{{$transaction->quantity}}L</td>
                                            <td>{{$transaction->reg_num}}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Recent Cash Sales</h4>
                            <p class="card-title-desc">Last 10 Fuel Allocations issued to {{$user->first_name}}.</p>

                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Car Reg</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($previousCash as $transaction)
                                        <tr>
                                            <td>{{$transaction->created_at}}</td>
                                            <td>{{$transaction->ftype}}</td>
                                            <td>{{$transaction->quantity}}L</td>
                                            <td>{{$transaction->reg_num}}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
        </div>
    </div>
@endsection
