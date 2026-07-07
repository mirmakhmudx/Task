@component('mail::message')
# E'loningiz tasdiqlandi!

Salom, **{{ $advert->user->name }}**!

Sizning **"{{ $advert->title }}"** nomli e'loningiz moderatsiyadan muvaffaqiyatli o'tdi va hozir faol holda ko'rsatilmoqda.

@component('mail::button', ['url' => route('adverts.show', $advert), 'color' => 'success'])
E'lonni ko'rish
@endcomponent

Agar savollaringiz bo'lsa, murojaat qoldiring.

Rahmat,
**{{ config('app.name') }}**
@endcomponent
