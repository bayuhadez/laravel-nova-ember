@component('mail::message')
# Information

<p>Pengajuan Anda Menjadi Mentor {{ config('app.name') }} telah ditolak.</p>
<p>Silahkan melakukan pengajuan ulang.</p>

@if(!empty($license->comment))
# Comment
<p>{{ $license->comment }}</p>
@endif

@component('mail::button', ['url' => config('app.url_frontend_login')])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
