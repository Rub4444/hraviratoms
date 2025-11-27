<!DOCTYPE html>
<html lang="ru" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>LoveLeaf Admin • Вход</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind CDN + шрифты --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet"
    >
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full bg-[#FAF8F4] font-[Inter] antialiased">
<div class="flex min-h-full items-center justify-center px-4 py-10">
    <div class="w-full max-w-sm rounded-3xl border border-slate-200 bg-white p-6 shadow-xl">
        <div class="mb-5 text-center">
            <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-white">
                LL
            </div>
            <h1 class="text-lg font-semibold text-slate-900">
                LoveLeaf Admin
            </h1>
            <p class="mt-1 text-xs text-slate-500">
                Войдите в панель управления приглашениями
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-3 rounded-xl bg-red-50 px-3 py-2 text-xs text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.perform') }}" class="space-y-3">
            @csrf

            <div>
                <label class="block text-[11px] font-medium text-slate-600">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                >
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-600">
                    Пароль
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                >
            </div>

            <div class="flex items-center justify-between text-xs text-slate-600">
                <label class="inline-flex items-center gap-1">
                    <input
                        type="checkbox"
                        name="remember"
                        class="h-3 w-3 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500"
                    >
                    Запомнить меня
                </label>
            </div>

            <button
                type="submit"
                class="mt-2 inline-flex w-full items-center justify-center rounded-full bg-emerald-500 px-4 py-2 text-xs font-medium text-white shadow-sm hover:bg-emerald-600"
            >
                Войти
            </button>
        </form>
    </div>
</div>
</body>
</html>
