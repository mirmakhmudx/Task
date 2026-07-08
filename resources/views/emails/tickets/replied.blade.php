@component('mail::message')
# Murojaatingizga javob keldi

Salom, **{{ $ticket->user->name }}**!

**#{{ $ticket->id }} — {{ $ticket->subject }}** murojaatingizga operator javob yozdi:

@component('mail::panel')
{{ $replyText }}
@endcomponent

@component('mail::button', ['url' => route('cabinet.tickets.show', $ticket)])
To'liq javobni ko'rish
@endcomponent

Rahmat,
**{{ config('app.name') }}**
@endcomponent
