@component('mail::message')
# Report Update

<p>Reference #: {{strtoupper($ref)}}</p>
<p>{{$message}}</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
