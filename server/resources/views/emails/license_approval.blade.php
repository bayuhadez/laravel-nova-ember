@component('mail::message')
# Congratulation

Pengajuan Anda Menjadi Mentor {{ config('app.name') }} telah disetujui.

@component('mail::button', ['url' => config('app.url_frontend_login')])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
