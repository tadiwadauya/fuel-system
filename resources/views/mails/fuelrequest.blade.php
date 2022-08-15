@component('mail::message')
    {{ $details['greeting'] }}

    {{ $details['body'] }}

    <h4>Request Summary</h4>
    <table style="border: 1px solid black; border-collapse: collapse; width: 100%">
        <tr>
            <td style="border: 1px solid black;border-collapse: collapse;"><strong>Type of Request</strong></td>
            <td style="border: 1px solid black;border-collapse: collapse;">{{ $details['body1'] }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;border-collapse: collapse;"><strong>Quantity in Litres</strong></td>
            <td style="border: 1px solid black;border-collapse: collapse;">{{ $details['body2'] }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;border-collapse: collapse;"><strong>Amount (zwl)</strong></td>
            <td style="border: 1px solid black;border-collapse: collapse;">{{ $details['body3'] }}</td>
        </tr>
    </table>


    {{ $details['body3'] }}

    @component('mail::button', ['url' => $details['approveURL']])
        Approve
    @endcomponent

    {{ $details['body4'] }}


    <table style="width: 100%">
        <tr>
            <td>
                @component('mail::button', ['url' => $details['previewURL']])
                    Review First
                @endcomponent
            </td>
            <td>
                @component('mail::button', ['url' => $details['rejectURL']])
                    Reject
                @endcomponent
            </td>
        </tr>
    </table>

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
