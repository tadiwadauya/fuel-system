<?php
/**
 *Created by PhpStorm for WhelsonFuelSystem
 *User: Vincent Guyo
 *Date: 31/7/2020
 *Time: 12:27 AM
 */

?>
@extends('layouts.app')

@section('template_title')
    Service Station Containers Report
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
                    <h4 class="page-title mb-1">Service Station Containers Report</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
                        <li class="breadcrumb-item active">Service Station Containers Report</li>
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
                            {!! Form::open(array('route' => 'containers.report', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-lg-3">
                                    <label>Get Transactions for:</label>
                                    <div class="input-group">
                                        <select class="custom-select form-control dynamic" name="customer" id="customer">
                                            <option value="">Please select customer</option>
                                            @if ($clients)
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}" >{{ $client->cli_name }} - {{ $client->cli_contact }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label>Reporting for Container:</label>
                                    <div class="input-group">
                                        <select class="custom-select form-control dynamic" name="container" id="container" required>
                                            <option value="">Please select container</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group mb-4">
                                        <label>Range of dates</label>
                                        <input type="text" name="date_range" class="form-control datepicker-here" data-range="true" data-multiple-dates-separator=" / " data-language="en" />
                                    </div>
                                </div>

                                <div class="col-lg-2 float-right">
                                    {!! Form::button('Get Report', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                                </div>
                            </div>

                            {!! Form::close() !!}

                            @if(isset($invoices))
                                <strong class="text-info">Opening balance: {{$containerInfo->concapacity}}L </strong>
                                <br><br>
                                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Voucher</th>
                                        <th>Car Reg N<sup>o</sup></th>
                                        <th>Driver</th>
                                        <th>Rate</th>
                                        <th>Quantity</th>
                                        <th>Value</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @php $tlitres=0;$tvalue=0; @endphp
                                    @foreach($invoices as $invoice)
                                        @php
                                            $value = number_format($invoice->quantity * $invoice->conrate, 2,'.', '');
                                            $tlitres+=$invoice->quantity;
                                            $tvalue+=$value;
                                        @endphp
                                        <tr>
                                            <td>{{$invoice->created_at}}</td>
                                            <td>{{$invoice->voucher}}</td>
                                            <td>{{$invoice->reg_num}}</td>
                                            <td>{{$invoice->driver}}</td>
                                            <td>{{$invoice->conrate}}</td>
                                            <td>{{$invoice->quantity}}</td>
                                            <td>{{$value}}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                <strong class="text-info">Showing Invoices for {{$userInfo->cli_name}},({{$containerInfo->conname}} which now has a balance of {{$containerInfo->conbalance}}L) with a total litres of {{$tlitres}}L worth ${{$tvalue}}</strong>

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

        $("#container").select2({
            placeholder: 'Please select a container.',
            allowClear:true,
        });
    </script>

    <script type="text/javascript">
        $('#customer').select2({
            placeholder: 'Please select a client.',
            allowClear:true,
        }).change(function(){
            var conid = $(this).val();
            var _token = $("input[name='_token']").val();
            if(conid){
                $.ajax({
                    type:"get",
                    url:'{{url('/getContainers')}}/'+conid,
                    _token: _token ,
                    success:function(res) {
                        if(res) {
                            $("#container").empty();
                            $.each(res,function(key, value){
                                $("#container").append('<option value="'+value.conname+'">'+value.conname+' - '+value.conbalance+'L Left</option>');
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
