<!DOCTYPE html>
<html lang="hy" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>LoveLeaf • Հայտ հրավիրատոմսի համար</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              leaf: {
                DEFAULT: '#0f766e',
                deep: '#115e59',
              },
              gold: {
                DEFAULT: '#b8860b',
              }
            },
            fontFamily: {
              display: ['"Playfair Display"', 'serif'],
              body: ['Inter', 'system-ui', 'sans-serif'],
            },
          },
        },
      }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Dancing+Script:wght@400;600&family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;600&display=swap"
        rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-50 font-body">
<div class="mx-auto max-w-3xl px-4 py-6">
    {{-- Хедер --}}
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('landing') }}" class="flex items-center gap-2">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-leaf text-white text-xs font-semibold">
                LL
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-semibold tracking-wide">LoveLeaf</span>
                <span class="text-[11px] text-slate-500">Online Wedding Invites</span>
            </div>
        </a>
    </div>

    <h1 class="text-2xl font-display font-semibold text-slate-900">
        Հայտ օնլայն հրավիրատոմսի ստեղծման համար
    </h1>
    <p class="mt-2 text-sm text-slate-700">
        Լրացրեք տեղեկությունները ձեր հարսանիքի մասին, և մենք կստեղծենք
        <span class="font-semibold">ինքնուրույն հրավիրատոմս</span> LoveLeaf հարթակում։
        Հայտը սկզբում կունենա «pending» կարգավիճակ, իսկ մեր ադմինը այն կվերանայի և կհրապարակի։
    </p>

    {{-- Успешное сообщение --}}
    @if(session('request_success'))
        <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            Ձեր հայտը հաջողությամբ ուղարկվել է 🥰<br>
            Մեր թիմը շուտով կկապվի Ձեզ հետ մանրամասները ճշտելու համար։
        </div>
    @endif

    {{-- Ошибки --}}
    @if($errors->any())
        <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            <div class="font-semibold mb-1">Խնդրում ենք ստուգել դաշտերը.</div>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Форма --}}
    <form action="{{ route('invitation.request.submit') }}" method="POST" class="mt-6 space-y-6">
        @csrf

        {{-- Выбор шаблона --}}
        <div class="rounded-2xl border bg-white p-4 shadow-sm">
            <h2 class="text-sm font-semibold text-slate-800 mb-3">
                1. Ընտրեք դիզայնը
            </h2>

            <div class="grid gap-3 md:grid-cols-2">
                @foreach($templates as $template)
                    @php
                        $checked = old('template_key', optional($selectedTemplate)->key) === $template->key;
                    @endphp
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border px-3 py-2 text-xs
                        {{ $checked ? 'border-leaf bg-emerald-50' : 'border-slate-200 hover:border-leaf/60' }}">
                        <input
                            type="radio"
                            name="template_key"
                            value="{{ $template->key }}"
                            class="mt-1"
                            @checked($checked)
                        >
                        <div>
                            <div class="font-semibold text-slate-900">{{ $template->name }}</div>
                            <div class="mt-0.5 text-[11px] text-slate-600">
                                {{ $template->description }}
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            <p class="mt-2 text-[11px] text-slate-500">
                Կարող եք նախ դիտել դիզայնները գլխավոր էջում, ապա վերադառնալ այս ձևին։
            </p>
        </div>

        {{-- Данные пары и события --}}
        <div class="rounded-2xl border bg-white p-4 shadow-sm space-y-4">
            <h2 class="text-sm font-semibold text-slate-800">
                2. Տեղեկություն հարսանիքի մասին
            </h2>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Հարսնացուի անունը *
                    </label>
                    <input type="text" name="bride_name"
                           value="{{ old('bride_name') }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-leaf focus:outline-none focus:ring-1 focus:ring-leaf">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Փեսայի անունը *
                    </label>
                    <input type="text" name="groom_name"
                           value="{{ old('groom_name') }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-leaf focus:outline-none focus:ring-1 focus:ring-leaf">
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Ամսաթիվ
                    </label>
                    <input type="date" name="date"
                           value="{{ old('date') }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-leaf focus:outline-none focus:ring-1 focus:ring-leaf">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Ժամ
                    </label>
                    <input type="text" name="time"
                           placeholder="օր.՝ 16:00"
                           value="{{ old('time') }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-leaf focus:outline-none focus:ring-1 focus:ring-leaf">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Dress code
                    </label>
                    <input type="text" name="dress_code"
                           placeholder="օր.՝ կանաչ, սպիտակ..."
                           value="{{ old('dress_code') }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-leaf focus:outline-none focus:ring-1 focus:ring-leaf">
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Զարգարահանդեսի վայրի անունը
                    </label>
                    <input type="text" name="venue_name"
                           value="{{ old('venue_name') }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-leaf focus:outline-none focus:ring-1 focus:ring-leaf">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Հասցե
                    </label>
                    <input type="text" name="venue_address"
                           value="{{ old('venue_address') }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-leaf focus:outline-none focus:ring-1 focus:ring-leaf">
                </div>
            </div>
        </div>

        {{-- Контакты клиента --}}
        <div class="rounded-2xl border bg-white p-4 shadow-sm space-y-4">
            <h2 class="text-sm font-semibold text-slate-800">
                3. Կոնտակտային տվյալներ
            </h2>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Ձեր անունը
                    </label>
                    <input type="text" name="client_name"
                           value="{{ old('client_name') }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-leaf focus:outline-none focus:ring-1 focus:ring-leaf">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Հեռախոսահամար * (WhatsApp / Telegram)
                    </label>
                    <input type="text" name="client_phone"
                           value="{{ old('client_phone') }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-leaf focus:outline-none focus:ring-1 focus:ring-leaf">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Էլ. հասցե
                </label>
                <input type="email" name="client_email"
                       value="{{ old('client_email') }}"
                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-leaf focus:outline-none focus:ring-1 focus:ring-leaf">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Լրացուցիչ նշումներ
                </label>
                <textarea name="client_notes" rows="3"
                          class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-leaf focus:outline-none focus:ring-1 focus:ring-leaf"
                          placeholder="օր.՝ որքան շուտ է հարկավոր հրավիրատոմսը, լեզուներ, առանձնահատկություններ և այլն։">{{ old('client_notes') }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('landing') }}" class="text-xs text-slate-500 hover:text-slate-700">
                ← Վերադառնալ գլխավոր էջ
            </a>

            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-full bg-leaf px-5 py-2.5 text-sm font-medium text-white shadow hover:bg-leaf-deep focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-leaf">
                Ուղարկել հայտը
            </button>
        </div>

        @if($demoInvitation)
            <input
                type="hidden"
                name="demo_payload"
                value="{{ json_encode($demoInvitation) }}"
            >
        @endif

    </form>
</div>
</body>
</html>
