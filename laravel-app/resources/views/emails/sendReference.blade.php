@component('mail::message')
# Report Submitted

Your report has been submmited for review and approval. Save this reference number to track your report.

<div
 style="margin:auto;width:100%;padding:5px;color:#000000;text-align:
  center; justify-content:center;font-size:25px;font-weight:bolder"
 ><p>{{$ref}}</p></div>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
