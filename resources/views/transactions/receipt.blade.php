<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 5/21/2020
 * Time: 11:20 AM
 */

$userInfo = \App\Models\User::where('paynumber', $transaction->employee )->firstOrFail();
$done_by = \App\Models\User::where('name', $transaction->done_by)->firstOrFail();
$allocation = \App\Models\Allocation::where('paynumber', $transaction->employee )->firstOrFail();
?>

<html>
<head>
    <style>
        body {font-family: sans-serif;
            font-size: 10pt;
        }
        p {	margin: 0pt; }
        table.items {
            border: 0.1mm solid #000000;
        }
        td { vertical-align: top; }
        .items td {
            border-left: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        table thead td { background-color: #EEEEEE;
            text-align: center;
            border: 0.1mm solid #000000;
            font-variant: small-caps;
        }
        .items td.blanktotal {
            background-color: #EEEEEE;
            border: 0.1mm solid #000000;
            background-color: #FFFFFF;
            border: 0mm none #000000;
            border-top: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        .items td.totals {
            text-align: right;
            border: 0.1mm solid #000000;
        }
        .items td.cost {
            text-align: "." center;
        }
    </style>
</head>
<body>
<!--mpdf
<htmlpageheader name="myheader">
<table width="100%">
<tr>
<td width="50%" style="text-align: left;"><span style="font-weight: bold; font-size: 14pt;">Allocation Transaction Receipt</span><br /></td>
<td width="50%" style="text-align: right;"><span style="font-weight: bold; font-size: 14pt;">Whelson Transport</span><br />64 Lytton Road<br />Workington<br />Harare<br /><span style="font-family:dejavusanscondensed;"></span> Zimbabwe</td>

</tr>
</table>
</htmlpageheader>
<htmlpagefooter name="myfooter">
<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
Page {PAGENO} of {nb}
</div>
</htmlpagefooter>
<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<div style="text-align: right">Date: {{$transaction->created_at}} </div>
<table width="100%" style="font-family: serif;" cellpadding="10"><tr>
        <td width="45%" style="border: 0.1mm solid #888888; "><span style="font-size: 7pt; color: #555555; font-family: sans;">Issue To:</span><br /><br />{{$userInfo->first_name}} {{$userInfo->last_name}} - {{$userInfo->paynumber}}</td>
        <td width="10%">&nbsp;</td>
        <td width="45%" style="border: 0.1mm solid #888888;"><span style="font-size: 7pt; color: #555555; font-family: sans;">Issued By:</span><br /><br />{{$done_by->first_name}} {{$done_by->last_name}}</td>
    </tr></table>
<br />
<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
    <thead>
    <tr>
        <td width="20%">Transaction Code</td>
        <td width="40%">Description</td>
        <td width="10%">Quantity (L)</td>
        <td width="10%">Amount (ZWL)</td>
        <td width="15%">Allocation</td>
        <td width="15%">Remaining</td>
    </tr>
    </thead>
    <tbody>
    <!-- ITEMS HERE -->
    <tr>
        <td align="center">{{$transaction->trans_code}}<br /></td>
        <td>Voucher Number: {{$transaction->voucher}}<br />Fuel Type: {{$transaction->ftype}}<br />Vehicle Reg Number: {{$transaction->reg_num}}<br /></td>
        <td align="center">{{$transaction->quantity}}<br /></td>
        <td align="center">{{$transaction->amount}}<br /></td>
        <td class="cost">{{$allocation->allocation}}<br /></td>
        <td class="cost">{{$allocation->balance}}<br /></td>
    </tr>

    </tbody>
</table>
<div style="text-align: center; font-style: italic;">Whelson GDC Transport - To be the Logistics Leader in our part of the world.</div>
</body>
</html>
