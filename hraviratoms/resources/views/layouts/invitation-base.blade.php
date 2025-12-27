<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @vite(['resources/css/app.css'])

    @stack('head')
</head>

<body>
    <div class="invite-page @yield('theme')">
        <div class="invite-card">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
