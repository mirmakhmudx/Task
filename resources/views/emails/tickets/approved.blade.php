@component('mail::message')
# Murojaatingiz ko'rib chiqildi

Salom, **{{ $ticket->user->name }}**!

**#{{ $ticket->id }} — {{ $ticket->subject }}** murojaatingiz ko'rib chiqilib, tasdiqlandi. Operator tez orada javob beradi.

@component('mail::button', ['url' => route('cabinet.tickets.show', $ticket)])
Murojaatni ko'rish
@endcomponent

Rahmat,
**{{ config('app.name') }}**
@endcomponent
