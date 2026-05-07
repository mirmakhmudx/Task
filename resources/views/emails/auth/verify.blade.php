@component('mail::message')
    # Salom, {{ $user->name }}!

    Ro'yxatdan o'tganingiz uchun rahmat. Iltimos, pastdagi tugmani bosish orqali emailingizni tasdiqlang:

    <a href="{{ route('register.verify', ['token' => $user->verify_token]) }}">Verify Email</a>
    Rahmat,<br>
    {{ config('app.name') }}
@endcomponent
