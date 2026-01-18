@php
  $design =
    $invitation->data['design']
    ?? $invitation->template->config['design']
    ?? [];

  $colors = $design['colors'] ?? [];
  $fonts  = $design['fonts'] ?? [];

  $theme =
    $invitation->data['design']['theme']
    ?? $invitation->template->config['theme']
    ?? null;
@endphp

<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/lightbox.js'])

    @stack('head')
</head>

<body>
    <div
        class="invite-page {{ $theme ? 'theme-'.$theme : '' }}"
        style="
            --color-primary: {{ $colors['primary'] ?? '' }};
            --color-accent: {{ $colors['accent'] ?? '' }};
            --color-bg: {{ $colors['background'] ?? '' }};
            --font-heading: {{ $fonts['heading'] ?? '' }};
            --font-body: {{ $fonts['body'] ?? '' }};
        "
        >


        <div class="invite-card">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
