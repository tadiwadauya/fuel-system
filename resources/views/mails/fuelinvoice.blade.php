@component('mail::message')
    {{$details['greeting']}}

<head>

<title>Whelson Fuel Invoice</title>

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
<strong> <span>Invoice To:</span> </strong><br>
<span>{{$details['cli_name']}}</span>
@if($details['cli_email'])<div class="address">{{$details['cli_email']}}</div>@endif
@if($details['cli_email_cc'])<div class="email">{{$details['cli_email_cc']}}</div>@endif
</address>
</div>

<div id="banktwo" class="col-lg-6 float-right">
<table style="width: 100%">
<tbody>
<tr>
<th>Ref: </th>
<td class="text-right">{{$details['trans_code']}}</td>
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
<th style="padding: 5px"><div> Invoice Total: </div></th>
<td style="padding: 5px" class="text-right"><strong> ${{$details['amount']}} </strong></td>
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
<td class="desc"> Diesel <br> Container: {{$details['conname']}} <br> Voucher: {{$details['voucher']}} <br> Issued Into: {{$details['reg_num']}} </td>
<td class="unit" style="text-align: center">{{$details['conrate']}}</td>
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
<th ><div>Invoice Total:  </div></th>
<td style="padding: 5px" class="text-right"><strong> ${{$details['amount'] }} </strong></td>
</tr>
</tbody>
</table>
</div>
</div>

<div style="margin-bottom: 0px">&nbsp;</div>

</div>
<br>
<div id="thanks">Please call again.</div>
</body>

@endcomponent

