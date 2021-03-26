@component('mail::message')
# Hi There! Selamat Datang di {{ config('app.name') }}

Kami telah menyediakan seminar dan course yang bermanfaat bagi Anda.
Silahkan masuk ke dalam {{ config('app.name')}}

@component('mail::button', ['url' => config('app.url_frontend_login')])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

