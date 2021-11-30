@component('mail::message')
{{$details['greeting']}}

{{$details['body']}}

<h4>Request Summary</h4>
<table style="border: 1px solid black; border-collapse: collapse; width: 100%">
<tr>
<td style="border: 1px solid black;border-collapse: collapse;"><strong>Type of Request</strong></td>
<td style="border: 1px solid black;border-collapse: collapse;">{{$details['ftype']}}</td>
</tr>
<tr>
<td style="border: 1px solid black;border-collapse: collapse;"><strong>Quantity in Litres</strong></td>
<td style="border: 1px solid black;border-collapse: collapse;">{{$details['quantity']}}</td>
</tr>
<tr>
    <td style="border: 1px solid black;border-collapse: collapse;"><strong>Amount (zwl)</strong></td>
    <td style="border: 1px solid black;border-collapse: collapse;">{{$details['amount']}}</td>
    </tr>
</table>


{{$details['link']}}

@component('mail::button', ['url' => $details['downloadUrl']])
View form
@endcomponent





Thanks,<br>
{{ config('app.name') }}
@endcomponent

