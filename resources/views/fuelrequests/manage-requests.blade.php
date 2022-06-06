<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 7/1/2020
 * Time: 3:16 AM
 */
?>
@extends('layouts.app')

@section('template_title')
    Manage Fuel Requests
@endsection

@section('template_linked_css')
    <!-- DataTables -->
    <link href="{{ url('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ url('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Fuel Requests</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/frequests') }}">Fuel Requests</a></li>
                        <li class="breadcrumb-item active">Approve/Reject Fuel Requests</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{ url('/frequests') }}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Fuel Requests
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
                            <h4 class="header-title">Requests</h4>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        {{-- <th>Request Type</th> --}}
                                        <th>Employee</th>
                                        <th>Quantity</th>
                                        <th>Amount (ZWL))</th>
                                        <th>Fuel Type</th>
                                        <th>Status</th>
                                        <th>Requested On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($frequests as $frequest)
                                        @php
                                            $user = \App\Models\User::where('paynumber', $frequest->employee)->first();
                                        @endphp
                                        <tr>
                                            {{-- <td>{{ $frequest->request_type }}</td> --}}
                                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                            <td>{{ $frequest->quantity }}</td>
                                            <td>{{ $frequest->amount }}</td>
                                            <td>{{ $frequest->ftype }}</td>
                                            <td>Pending Approval</td>
                                            <td>{{ $frequest->created_at }}</td>
                                            <td style="white-space: nowrap;">

                                                <a class="btn btn-sm btn-outline-info btn-rounded waves-effect waves-light"
                                                    href="{{ URL::to('/frequests/preview/' . $frequest->id) }}">
                                                    Info
                                                </a>

                                                <a class="btn btn-sm btn-success"
                                                    href="{{ URL::to('/frequests/approve/' . $frequest->id) }}">
                                                    Approve
                                                </a>

                                                <a class="btn btn-sm btn-danger"
                                                    href="{{ URL::to('/frequests/reject/' . $frequest->id) }}">
                                                    Reject
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
    <script src="{{ url('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ url('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ url('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ url('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ url('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ url('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ url('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ url('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ url('assets/js/pages/datatables.init.js') }}"></script>
@endsection
