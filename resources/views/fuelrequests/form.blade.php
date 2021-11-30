<?php
/**
 * Created by PhpStorm for wls
 * User: Vincent Guyo
 * Date: 1/11/2020
 * Time: 2:43 AM
 */


$employee = \App\Models\User::where('paynumber',$frequest->employee)->first();
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
<htmlpageheader name="myheader">
    <strong><p style="text-align: center; font-size: 14pt; font-weight: bold;">WHELSON TRANSPORT <br/>
            Fuel Request Quotation</p></strong>
</htmlpageheader>

<sethtmlpageheader name="myheader" value="on" show-this-page="1"/>

<table border="1" width="100%" style="font-family: Arial; border-collapse: collapse; font-size: 10pt;" cellpadding="5">
    <tr>
        <td style="width: 23%;">DATE &nbsp;&nbsp;<strong>{{' '.date('M j Y', strtotime($frequest->created_at))}}</strong></td>
        <td style="width: 60%;">DEPARTMENT &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>{{' '.$frequest->department}}</strong></td>
        <td style="width: 17%;">PAY NO</td>
    </tr>

    <tr>
        <td>NAME</td>
        <td style="text-align: center;"><strong> {{$employee->first_name.' '.$employee->last_name}}</strong></td>
        <td style="text-align: center;"><strong>{{$employee->paynumber}}</strong></td>
    </tr>



</table>
<br/>
<p style="font-weight: bold; text-align: center; font-size: 10pt;">Requests Details</p>
<br/>

<table border="1" width="100%" style="font-family: Arial; border-collapse: collapse; font-size: 10pt;" cellpadding="5">
    <tr>
        <td colspan="2" style="font-weight: bold; text-align: center;">
            Request Type: {{$frequest->request_type}}

        </td>
    </tr>

    <tr>
        <td style="width: 23%;">Quantity: </td>
        <td style="width: 77%; text-align: center;">  {{$frequest->quantity}}</td>
    </tr>

    <tr>
        <td>Amount:</td>
        <td style="text-align: center;"> {{$frequest->amount}}</td>
    </tr>

    <tr>
        <td>Fuel Type:</td>
        <td style="text-align: center;">  {{$frequest->ftype}}</td>
    </tr>
    <tr>
        <td>Status:</td>
        <td style="text-align: center;">     @if($frequest->status == 1) Approved @else Pending Approval @endif</td>
    </tr>
    <tr>
        <td>Approved By:</td>
        <td style="text-align: center;">     {{$frequest->approved_by}}</td>
    </tr>
    <tr>
        <td>Approved When: </td>
        <td style="text-align: center;">   {{$frequest->approved_when}}</td>
    </tr>
    <tr>
        <td>Requested On:</td>
        <td style="text-align: center;">   {{$frequest->created_at}}</td>
    </tr>

</table>


</body>
</html>

