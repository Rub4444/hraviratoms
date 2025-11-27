<!DOCTYPE html>
<html lang="hy" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>Հրավիրատոմսը չի գտնվել | Online Wedding Invite</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind через CDN, чтобы не заморачиваться с Vite на 404 --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full bg-slate-950 text-slate-50 antialiased">
<div class="flex min-h-full items-center justify-center px-4 py-10">
    <div
        class="relative w-full max-w-2xl overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-b from-slate-900/80 to-slate-950 shadow-2xl"
    >
        {{-- Декор --}}
        <div class="pointer-events-none absolute -left-24 -top-24 h-48 w-48 rounded-full bg-rose-500/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-10 bottom-0 h-40 w-40 rounded-full bg-amber-400/20 blur-3xl"></div>

        <div class="relative px-7 pb-8 pt-8 sm:px-10 sm:pt-10">
            <p class="text-xs font-medium uppercase tracking-[0.25em] text-slate-400">
                404 • Էջը չի գտնվել
            </p>

            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-white sm:text-4xl">
                Այս հրավիրատոմսը այլևս հասանելի չէ 💌
            </h1>

            <p class="mt-3 text-sm leading-relaxed text-slate-300">
                Հնարավոր է որ հղումը սխալ է, հրավիրատոմսը ջնջվել է կամ ժամկետն ավարտվել է։
                Բայց դուք կարող եք ստեղծել
                <span class="font-semibold text-rose-300">ձեր սեփական օնլայն հրավիրատոմսը</span>
                նույնպիսի գեղեցիկ ձևաչափով։
            </p>

            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl bg-white/5 p-4 text-xs text-slate-200">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                        Ինչ եք ստանում
                    </p>
                    <ul class="mt-2 space-y-1.5">
                        <li>• Անհատական էջ՝ ձեր անուններով, ամսաթվով ու վայրով</li>
                        <li>• Գեղեցիկ դիզայն (Elegant, Nature, Luxury, Pastel)</li>
                        <li>• Հարմար հղում՝ WhatsApp / Telegram-ի համար</li>
                        <li>• Ծրագիր, քարտեզ, dress code և ավելին</li>
                    </ul>
                </div>

                <div class="rounded-2xl bg-white/5 p-4 text-xs text-slate-200">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                        Ինչպես պատվիրել
                    </p>
                    <ul class="mt-2 space-y-1.5">
                        <li>1. Մուտք գործեք admin բաժին</li>
                        <li>2. Ընտրեք դիզայներական template</li>
                        <li>3. Գրեք ձեր տվյալները</li>
                        <li>4. Ստացեք հղում և ուղարկեք հյուրերին</li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap items-center gap-3">
                {{-- CTA: создать приглашение --}}
                <a
                    href="/admin"
                    class="inline-flex items-center justify-center rounded-full bg-rose-500 px-5 py-2 text-xs font-medium text-white shadow-lg shadow-rose-500/40 hover:bg-rose-600"
                >
                    Ստեղծել օնլայն հրավիրատոմս
                </a>

                {{-- CTA: посмотреть примеры --}}
                <a
                    href="/admin"
                    class="inline-flex items-center justify-center rounded-full border border-slate-500/60 px-4 py-2 text-xs font-medium text-slate-100 hover:bg-white/5"
                >
                    Դիտել դիզայնների օրինակները
                </a>

                <p class="text-[11px] text-slate-400">
                    Կամ պարզապես փակեք պատուհանը, եթե հյուր եք 😊
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
