@component('mail::message')
    {{$details['greeting']}}

<head>

<title>Whelson Fuel Quotation</title>

<style>

.text-right {
text-align: right;
}

#client {
padding-left: 6px;
border-left: 6px solid #e32239;
float: left;
}

#banktwo {
padding-right: 6px;
border-right: 6px solid #e32239;
float: right;
}

.inner-body {
    font-family: Nunito, sans-serif;
    box-sizing: border-box;
    background-color: #FFFFFF;
    margin: 0 auto;
    padding: 0;
    width: 800px !important;
    -premailer-cellpadding: 0;
    -premailer-cellspacing: 0;
}

#thanks{
    font-size: 2em;
    margin-bottom: 50px;
}
</style>

</head>
<body class="inner-body" style="background: white; width: 100%;">

<div>
<div class="row col-lg-12">
<div id="client" class="col-lg-6 float-left">
<address>
<strong> <span>Quote To:</span> </strong><br>
<span>{{$details['client']}}</span>
@if($details['email'])<div class="address">{{$details['email']}}</div>@endif
@if($details['email_cc'])<div class="email">{{$details['email_cc']}}</div>@endif
</address>
</div>

<div id="banktwo" class="col-lg-6 float-right">
<table style="width: 100%">
<tbody>
<tr>
<th>Ref: </th>
<td class="text-right">{{$details['quote_num']}}</td>
</tr>
<tr>
<th>Date: </th>
<td class="text-right">{{$details['created_at']}}</td>
</tr>
<tr>
<th>Issued by: </th>
<td class="text-right">{{$details['done_by']}}</td>
</tr>
</tbody>
</table>

<div style="margin-bottom: 0px">&nbsp;</div>

<table style="width: 100%; margin-bottom: 20px">
<tbody>
<tr class="well" style="padding: 5px">
<th style="padding: 5px"><div> Quotation Total: </div></th>
<td style="padding: 5px" class="text-right"><strong>{{$details['currency']}} ${{$details['amount']}} </strong></td>
</tr>
</tbody>
</table>
</div>
</div>

<br><br><br><br><br><br><br>

<table class="table" style="width: 100%;">
<thead style="background: #F5F5F5;">
<tr >
<th width="40%"> <strong> DESCRIPTION </strong></th>
<th style="text-align: center"><strong> UNIT PRICE ($)</strong></th>
<th style="text-align: center"><strong> QUANTITY (L)</strong></th>
<th style="text-align: center" ><strong> TOTAL ($)</strong></th>
</tr>
</thead>
<tbody>
<tr>
<td class="desc"> Diesel <br> @if($details['notes']) Notes: {{$details['notes']}} @endif</td>
<td class="unit" style="text-align: center">{{$details['price']}}</td>
<td class="qty" style="text-align: center">{{$details['quantity']}}</td>
<td class="total" style="text-align: center">{{$details['amount']}}</td>
</tr>
</tbody>
</table>

<div class="row">
<div class="col-lg-6 float-left"></div>
<div class="col-lg-6 float-right text-right">
<table>
<tbody>
<tr class="well " style="padding-right: 5px; float: right">
<th ><div>Quotation Total:  </div></th>
<td style="padding: 5px" class="text-right"><strong> {{$details['currency']}} ${{$details['amount'] }} </strong></td>
</tr>
</tbody>
</table>
</div>
</div>

<div style="margin-bottom: 0px">&nbsp;</div>
<br>
<h1>Banking Details</h1>
<hr>
<br>
<div class="row">
<table style="width: 100%">
<tbody>
<tr>
@if ($details['currency'] == 'USD')
<td id="notices"> <div id="client"><strong> STANBIC BANK </strong><br>
ACCOUNT NAME: <strong>HALWICK INVESTMENTS</strong><br>
BRANCH: <strong>SOUTHERTON</strong><br>
BRANCH CODE: <strong>03120 </strong><br>
ACCOUNT NUMBER: <strong>914 000 3431 974 </strong><br>
SWIFT CODE: <strong>SBICZWHX </strong><br>
CURRENCY: <strong>UNITED STATES DOLLARS </strong><br>
</div>
</td>
@else
<td id="notices"> <div id="client"><strong> STANBIC BANK </strong><br>
ACCOUNT NAME: <strong>HALWICK INVESTMENTS</strong><br>
BRANCH: <strong>SOUTHERTON</strong><br>
BRANCH CODE: <strong>03120 </strong><br>
ACCOUNT NUMBER: <strong>914 0000 964 300 </strong><br>
SWIFT CODE: <strong>SBICZWHX </strong><br>
CURRENCY: <strong>ZWL </strong><br>
</div>
</td>
@endif
@if ($quotation->currency == 'USD')
<td class="text-right" >
<div id="banktwo" class="float-right text-right">
<strong>CBZ </strong><br>
ACCOUNT NAME: <strong>HALWICK INVESTMENTS </strong><br>
BRANCH: <strong>SOUTHERTON </strong><br>
ACCOUNT NUMBER: <strong>02321025120062 </strong><br>
SWIFT CODE: <strong>COBZZWHAXXX </strong><br>
CURRENCY: <strong>UNITED STATES DOLLARS </strong><br>
</div>
</td>
@else
<td class="text-right" >
<div id="banktwo" class="float-right text-right">
<strong>CABS </strong><br>
ACCOUNT NAME: <strong>HALWICK INVESTMENTS </strong><br>
BRANCH: <strong>PARK STREET </strong><br>
ACCOUNT NUMBER: <strong>100 476 9784 </strong><br>
CURRENCY: <strong>ZWL </strong><br>
</div>
</td>
@endif
</tr>

</tbody>
</table>

</div>

</div>
<br>
<div id="thanks">Hope you visit us soon.</div>
</body>

@endcomponent
