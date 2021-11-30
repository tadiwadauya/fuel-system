<?php
/**
 * Created by PhpStorm for wls
 * User: Vincent Guyo
 * Date: 1/11/2020
 * Time: 2:43 AM
 */



$client = \App\Models\Client::where('id',$invoice->client)->firstOrFail();
$container = \App\Models\Container::where('conname',$invoice->container)->firstOrFail();

$grandtotal = number_format(($container->conrate * $invoice->quantity), 2,'.', '') ;
//dd($userInfo->first_name);
?>
<html>
<head>
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
            margin: 0  0 0 0;
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

        table td {
        }

        table td h3{
            color: #000;
            font-size: 14px;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .no {
        }

        table .desc {
            text-align: left;
        }

        table .unit {

        }

        table .qty {
        }

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

        #thanks{
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices{
            padding-left: 6px;
            border-left: 6px solid #e32239;
        }

        #notices .notice {
            font-size: 14px;
        }


    </style>
</head>
<body>
    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="text-center">Transaction Summary</h1>
                                <div id="details" class="clearfix">
                                    <div id="client">
                                        <div class="to">INVOICE TO:</div>
                                        <h2 class="name">{{$client->cli_name}}</h2>
                                        <div class="address">{{$invoice->driver}}</div>
                                        <div class="email">{{$client->cli_email}}</div>
                                    </div>

                                    <div id="trans">
                                        <div style="text-align: right; margin-right: 10px;">
                                            <h1>{{$invoice->trans_code}}</h1>
                                            <div class="date">Date: {{$invoice->created_at}}</div>
                                            <div class="date">Issued By: {{$invoice->done_by}}</div>
                                        </div>
                                    </div>
                                </div>

                                <table >
                                    <thead >
                                    <tr >
                                        <th class="desc" width="40%"> <strong> DESCRIPTION </strong></th>
                                        <th class="unit" style="text-align: center"><strong> UNIT PRICE ($)</strong></th>
                                        <th class="qty"  style="text-align: center"><strong> QUANTITY (L)</strong></th>
                                        <th class="total"  style="text-align: center" ><strong> TOTAL ($)</strong></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="desc"> Container: <h3> {{$invoice->container}}</h3> Capacity: <strong> {{$container->concapacity}}L</strong> <br> Remaining: <strong>{{$container->conbalance}}L</strong> <br> Voucher: <strong>{{$invoice->voucher}}</strong> <br> Issued Into: <strong>{{$invoice->reg_num}}</strong></td>
                                        <td class="unit">{{$container->conrate}}</td>
                                        <td class="qty">{{$invoice->quantity}}</td>
                                        <td class="total">{{$container->conrate * $invoice->quantity }}</td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    {{--<tr>
                                        <td ></td>
                                        <td ></td>
                                        <td >SUBTOTAL</td>
                                        <td>{{$subtotal}}</td>
                                    </tr>
                                    <tr>
                                        <td ></td>
                                        <td ></td>
                                        <td >TAX 15%</td>
                                        <td>{{$tax}}</td>
                                    </tr>--}}
                                    <tr>
                                        <td ></td>
                                        <td ></td>
                                        <td >GRAND TOTAL</td>
                                        <td>{{$grandtotal}}</td>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div id="thanks">Good day!</div>
                                <div id="notices">
                                   <strong> Whelson Fuel </strong><br>
                                        64 Lytton Road, <br>
                                        Workington, Harare<br>
                                        Email: servicestation@whelson.co.zw <br>

                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

