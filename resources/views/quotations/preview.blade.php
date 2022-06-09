<?php
/**
 *Created by PhpStorm for WhelsonFuelSystem
 *User: Vincent Guyo
 *Date: 8/11/2020
 *Time: 6:19 PM
 */
?>
@extends('layouts.app')

@section('template_title')
    Quotation Summary
@endsection

@section('template_linked_css')
    <style type="text/css">
        #details {
            margin-bottom: 50px;
        }

        #client {
            padding-left: 6px;
            border-left: 6px solid #e32239;
            float: left;
        }

        #trans {
            padding-right: 6px;
            border-right: 6px solid #e32239;
            float: right;
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }

        #invoice {
            float: right;
            text-align: right;
        }

        #invoice h1 {
            color: #000;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 0 0;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 5px;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {}

        table td h3 {
            color: #000;
            font-size: 14px;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .no {}

        table .desc {
            text-align: left;
        }

        table .unit {}

        table .qty {}

        table .total {
            background: #DDDDDD;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 14px;
        }

        table tbody tr:last-child td {
            border: none;
        }

        table tfoot td {
            background: #FFFFFF;
            border-bottom: none;
            font-size: 14px;
            white-space: nowrap;
            border-top: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr:last-child td {
            color: #000;
            font-size: 1.4em;
            border-top: 1px solid #000;

        }

        table tfoot tr td:first-child {
            border: none;
        }

        #thanks {
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices {
            padding-left: 6px;
            border-left: 6px solid #e32239;
        }

        #notices .notice {
            font-size: 14px;
        }
    </style>
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <br>
                <div class="col-md-6">
                    <a href="{{ URL::previous() }}" class="btn btn-primary btn-rounded waves-effect waves-light"><i
                            class="mdi mdi-page-previous mr-1"></i> Back</a>
                </div>

                <div class="col-md-6">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{ url('/mail-quote/' . $quotation->id) }}"
                                type="button">
                                <i class="mdi mdi-mail mr-1"></i>Email
                            </a>

                            <a class="btn btn-light btn-rounded" href="{{ url('/quotationPdf/' . $quotation->id) }}"
                                target="_blank" type="button">
                                <i class="mdi mdi-printer mr-1"></i>Print
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
                            <h1 class="text-center">Quotation Summary</h1>
                            <div id="details" class="clearfix">
                                <div id="client">
                                    <div class="to">QUOTE TO:</div>
                                    <h2 class="name">{{ $quotation->client }}</h2>
                                    @if ($quotation->email)
                                        <div class="address">{{ $quotation->email }}</div>
                                    @endif
                                    @if ($quotation->email_cc)
                                        <div class="email">{{ $quotation->email_cc }}</div>
                                    @endif
                                </div>

                                <div id="trans">
                                    <div style="text-align: right; margin-right: 10px;">
                                        <h1>{{ $quotation->quote_num }}</h1>
                                        <div class="date">Date: {{ $quotation->created_at }}</div>
                                        <div class="date">Issued By: {{ $quotation->done_by }}</div>
                                    </div>
                                </div>
                            </div>

                            <table>
                                <thead>
                                    <tr>
                                        <th class="desc" width="40%"> <strong> DESCRIPTION </strong></th>
                                        <th class="unit" style="text-align: center"><strong> UNIT PRICE
                                                ($)</strong></th>
                                        <th class="qty" style="text-align: center"><strong> QUANTITY (L)</strong>
                                        </th>
                                        <th class="total" style="text-align: center"><strong> TOTAL ($)</strong>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="desc"> Diesel <br>
                                            @if ($quotation->notes)
                                                Notes: {{ $quotation->notes }}
                                            @endif
                                        </td>
                                        <td class="unit">{{ $quotation->price }}</td>
                                        <td class="qty">{{ $quotation->quantity }}</td>
                                        <td class="total">{{ $quotation->amount }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>

                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>GRAND TOTAL </td>
                                        <td>{{ $quotation->currency }} ${{ $quotation->amount }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <br><br><br>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="col-xl-6 float-left">
                                        <div id="notices">
                                            <h2>STANBIC BANK</h2>
                                            <div class="notice">
                                                <table>
                                                    <tr>
                                                        <td>ACCOUNT NAME:</td>
                                                        <td>REDAN COUPON</td>
                                                    </tr>
                                                    <tr>
                                                        <td>BRANCH:</td>
                                                        <td>MSASA</td>
                                                    </tr>

                                                    <tr>
                                                        <td>ACCOUNT NUMBER:</td>
                                                        <td>914 000 0931 844</td>
                                                    </tr>


                                                </table>
                                            </div>
                                        </div>




                                    </div>

                                    <div class="col-xl-6 float-right">
                                        <div id="notices">
                                            <h2>STANDARD BANK OF SOUTH AFRICA</h2>
                                            <div class="notice">
                                                <table>

                                                    <tr>
                                                        <td>SWIFT BIC CODE:</td>
                                                        <td>SBZAZAJJ</td>
                                                    </tr>
                                                    <tr>
                                                        <td>ACCOUNT NUMBER:</td>
                                                        <td>090861930</td>
                                                    </tr>
                                                    <tr>
                                                        <td>BENEFICIARY BANK NAME:</td>
                                                        <td>STANBIC BANK</td>
                                                    </tr>
                                                    <tr>
                                                        <td>BENEFICIARY BANK SWIFT CODE:</td>
                                                        <td>SBICZWHX</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>

                            <div id="thanks">Good day!</div>
                            <div id="notices">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
