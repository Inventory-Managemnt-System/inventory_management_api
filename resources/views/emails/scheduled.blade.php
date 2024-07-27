@component('mail::message')
# {{ $emailDetails['subject'] }}

{{ $emailDetails['message'] }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
