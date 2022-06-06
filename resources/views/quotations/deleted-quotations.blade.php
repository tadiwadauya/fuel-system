<?php
/**
 *Created by PhpStorm for WhelsonFuelSystem
 *User: Vincent Guyo
 *Date: 8/11/2020
 *Time: 9:16 PM
 */
?>
@extends('layouts.app')

@section('template_title')
    Showing Deleted Quotations
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
                    <h4 class="page-title mb-1">Deleted Quotations</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/quotations') }}">Quotations</a></li>
                        <li class="breadcrumb-item active">Deleted Quotations</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{ url('/quotations') }}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Quotations
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
                            <h4 class="header-title">Quotations</h4>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Quotation Ref</th>
                                        <th>Client</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                        <th>Prepared by</th>
                                        <th>Deleted On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($quotations as $quotation)
                                        <tr>
                                            <td>{{ $quotation->quote_num }}</td>
                                            <td>{{ $quotation->client }}</td>
                                            <td>{{ $quotation->price }}</td>
                                            <td>{{ $quotation->quantity }}</td>
                                            <td>{{ $quotation->amount }}</td>
                                            <td>{{ $quotation->done_by }}</td>
                                            <td>{{ $quotation->deleted_at }}</td>
                                            <td style="white-space: nowrap;">
                                                {!! Form::model($quotation, ['action' => ['SoftDeleteQuotation@update', $quotation->id], 'method' => 'PUT']) !!}
                                                {!! Form::button('<i class="mdi mdi-restore" aria-hidden="true"></i>', ['class' => 'btn btn-success btn-sm', 'type' => 'submit', 'data-toggle' => 'tooltip', 'title' => 'Restore Quotation']) !!}
                                                {!! Form::close() !!}

                                                <a class="btn btn-sm btn-info"
                                                    href="{{ URL::to('quotations/deleted/' . $quotation->id) }}">
                                                    <i class="mdi mdi-eye-outline" aria-hidden="true"></i>
                                                </a>

                                                {!! Form::model($quotation, ['action' => ['SoftDeleteQuotation@destroy', $quotation->id], 'method' => 'DELETE']) !!}
                                                {!! Form::hidden('_method', 'DELETE') !!}
                                                {!! Form::button('<i class="mdi mdi-trash-can-outline" aria-hidden="true"></i>', ['class' => 'btn btn-danger btn-sm inline', 'type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Quotation', 'data-message' => 'Are you sure you want to delete this quotation ?']) !!}
                                                {!! Form::close() !!}
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
