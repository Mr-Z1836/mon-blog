<x-mail::message>
# Nouveau message de contact

**Nom :** {{ $contactMessage->name }}

**E-mail :** {{ $contactMessage->email }}

**Message :**

{{ $contactMessage->message }}

<x-mail::subcopy>
Réponds directement à cet e-mail pour contacter {{ $contactMessage->name }}.
</x-mail::subcopy>
</x-mail::message>
