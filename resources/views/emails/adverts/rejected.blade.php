@component('mail::message')
# E'loningiz rad etildi

Salom, **{{ $advert->user->name }}**!

Afsuski, **"{{ $advert->title }}"** nomli e'loningiz quyidagi sabab bilan rad etildi:

@component('mail::panel')
{{ $advert->reject_reason ?? 'Sabab ko\'rsatilmagan.' }}
@endcomponent

E'lonni tahrirlash va qayta yuborish uchun quyidagi tugmani bosing:

@component('mail::button', ['url' => route('cabinet.adverts.edit', $advert), 'color' => 'error'])
E'lonni tahrirlash
@endcomponent

Rahmat,
**{{ config('app.name') }}**
@endcomponent
