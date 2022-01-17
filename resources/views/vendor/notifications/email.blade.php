@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Halo!')
@endif
@endif

{{-- Intro Lines --}}
Anda menerima email ini karena kami menerima permintaan reset password untuk akun anda.

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
Link reset password ini akan kedaluarsa dalam 60 menit. Jika anda tidak bermaksud mereset password, abaikan pesan ini.

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Salam Hangat,<br>
{{ config('setting.app_name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
    "Jika kamu mengalami masalah ketika menekan tombol \":actionText\" , Salin dan paste link di bawah\n".
    'ke dalam web browser anda:',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent
