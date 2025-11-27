<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wedding Invites Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/js/app.js')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Dancing+Script:wght@400;600&family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">

</head>
<body class="antialiased">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button
            type="submit"
            class="rounded-full border border-slate-300 px-3 py-1 text-xs text-slate-700 hover:bg-slate-100"
        >
            Выйти
        </button>
    </form>
    <div id="app"></div>
</body>
</html>
