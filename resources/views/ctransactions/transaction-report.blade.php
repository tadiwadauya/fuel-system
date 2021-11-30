<?php
/**
 *Created by PhpStorm for WhelsonFuelSystem
 *User: Tadiwan Dauya
 *Date: 31/7/2021
 *Time: 12:27 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    Service Station Container Transactions Report
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
    <!-- DataTables -->
    <link href="{{url('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{url('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Service Station Container Transactions Report</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
                        <li class="breadcrumb-item active">Service Station Container Transactions Report</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">

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
                            {!! Form::open(array('url' => 'transaction-report-post', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-lg-5">
                                    <label>Get Transactions for:</label>
                                    <div class="input-group">
                                        <select class="custom-select form-control dynamic" name="client" id="client" style="width: 100%;">
                                            <option value="">Please select customer</option>
                                            @if ($clients)
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}" >{{ $client->cli_name }} - {{ $client->cli_contact }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <label>Reporting for Container:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="container" id="container" required>
                                    </div>
                                </div>

                                <div class="col-lg-2 float-right">
                                    {!! Form::button('Get Report', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                </div>
                            </div>

                            {!! Form::close() !!}

                            @if(isset($transactions))
                            <h4 class="header-title mt-4">Showing all transactions for container no: {{ $cont_number }}</h4>
                            <br>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Container</th>
                                    <th>Batch</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Balance bf</th>
                                    <th>Balance Af</th>
                                    <th>Date</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{$transaction->client}}</td>
                                        <td>{{$transaction->container}}</td>
                                        <td>{{$transaction->batch}}</td>
                                        <td>{{$transaction->concapacity}}</td>
                                        <td>{{$transaction->conrate}}</td>
                                        <td>{{$transaction->b_before}}</td>
                                        <td>{{$transaction->b_after}}</td>
                                        <td>{{$transaction->created_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            @endif
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
        $('#client').select2({
                placeholder:'Please select a Client.',
                allowClear:true,
            }).change(function(){
                var id = $(this).val();
                var _token = $("input[name='_token']").val();
                if(id){
                    $.ajax({
                        type:"get",
                        url:"/getClientContainer/"+id,
                        _token: _token ,
                        success:function(res)
                        {
                            if(res)
                            {
                                $("#container").empty();
                                $.each(res,function(key, value){

                                    $("#container").val(value);
                                });

                            }
                        }

                    });
                }
            });

    </script>

    <!-- Required datatable js -->
    <script src="{{url('assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{url('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{url('assets/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{url('assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{url('assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
    <!-- Responsive examples -->
    <script src="{{url('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{url('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

    <!-- Datatable init js -->
    <script src="{{url('assets/js/pages/datatables.init.js')}}"></script>
@endsection
