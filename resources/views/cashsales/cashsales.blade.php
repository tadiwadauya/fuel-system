<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 5/20/2020
 * Time: 3:49 PM
 */
?>
@extends('layouts.app')

@section('template_title')
    Showing Cash Sales
@endsection

@section('template_linked_css')
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
                    <h4 class="page-title mb-1">Cash Sales</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/cashsales')}}">Cash Sales</a></li>
                        <li class="breadcrumb-item active">Cash Sales</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/cashsales/create')}}" type="button">
                                <i class="mdi mdi-plus-box mr-1"></i>Create Cash Sale
                            </a>
                        </div>
                    </div>
                    &nbsp;&nbsp;
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/cashsales/deleted')}}" type="button">
                                <i class="mdi mdi-trash-can mr-1"></i>Trashed Cash Sales
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Cash Sales</h4>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Transaction Code</th>
                                    <th>Employee</th>
                                    <th>Voucher</th>
                                    <th>Fuel</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th>Invoice No.</th>
                                    <th>Car Reg</th>
                                    <th>Done by</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($cashsales as $cashsale)
                                    @php
                                        $user = \App\Models\User::where('paynumber', $cashsale->employee)->first();
                                    @endphp
                                    <tr>
                                        <td>{{$cashsale->trans_code}}</td>
                                        <td>
                                            @if ($user)
                                            {{$user->first_name}} {{$user->last_name}}
                                            @endif
                                            </td>
                                        <td>{{$cashsale->voucher}}</td>
                                        <td>{{$cashsale->ftype}}</td>
                                        <td>{{$cashsale->quantity}}</td>
                                       <td>{{$cashsale->amount}}</td>
                                       <td>{{$cashsale->invoice_number}}</td>
                                        <td>{{$cashsale->reg_num}}</td>
                                        <td>{{$cashsale->done_by}}</td>
                                        <td style="white-space: nowrap;">
                                            {!! Form::open(array('url' => 'cashsales/' . $cashsale->id, 'class' => 'btn btn-sm btn-danger ', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                            {!! Form::hidden('_method', 'DELETE') !!}
                                            {!! Form::button('<i class="mdi mdi-trash-can-outline" aria-hidden="true"></i>' , array('class' => 'btn btn-sm btn-danger ','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Cash Sale', 'data-message' => 'Are you sure you want to delete this cash sale ?')) !!}
                                            {!! Form::close() !!}

                                            <a class="btn btn-sm btn-success" href="{{ URL::to('cashsales/' . $cashsale->id) }}" data-toggle="tooltip" title="Show">
                                                <i class="mdi mdi-eye-outline" aria-hidden="true"></i>
                                            </a>

                                            <a class="btn btn-sm btn-info" href="{{ URL::to('cashsales/' . $cashsale->id . '/edit') }}" data-toggle="tooltip" title="Edit">
                                                <i class="mdi mdi-database-edit" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>

    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')

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
