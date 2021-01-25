
@component('mail::message')
# New message from {{ $name }} with e-mail {{ $email }}

{{ $text}}

{{ config('app.name') }}
@endcomponent
