@component('mail::message')
# Yangi xabar keldi

Salom!

**{{ $sender->name }}** sizga **"{{ $dialog->advert->title }}"** e'loni bo'yicha xabar yozdi:

@component('mail::panel')
{{ $messageText }}
@endcomponent

@component('mail::button', ['url' => route('cabinet.dialogs.show', $dialog)])
Dialogni ochish
@endcomponent

Rahmat,
**{{ config('app.name') }}**
@endcomponent
