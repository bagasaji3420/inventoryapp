<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Notification' }}</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding:40px 10px;">
    <div style="max-width:500px; margin:auto; background:#ffffff; padding:30px; border-radius:10px; text-align:center;">

        {{-- HEADER --}}
        <h2 style="margin-bottom:10px;">
            Hello, {{ $name ?? 'User' }}
        </h2>

        {{-- CONTENT (dynamic part) --}}
        <div style="color:#555; margin:20px 0;">
            @include($view, $data ?? [])
        </div>

        {{-- BUTTON --}}
        @isset($buttonUrl)
            <a href="{{ $buttonUrl }}"
                style="background:{{ $buttonColor ?? '#696cff' }}; color:#fff; padding:12px 20px; border-radius:6px; text-decoration:none; display:inline-block;">
                {{ $buttonText ?? 'Click Here' }}
            </a>
        @endisset

        {{-- FOOTER NOTE --}}
        @isset($footer)
            <p style="color:#999; font-size:14px; margin-top:20px;">
                {{ $footer }}
            </p>
        @endisset

    </div>

    <div style="margin-top:30px; text-align:center; font-size:12px; color:#777;">
        © {{ $date ?? date('Y') }} {{ $appname ?? config('app.name') }} All rights reserved
    </div>
</body>

</html>