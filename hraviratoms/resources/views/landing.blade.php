{{-- resources/views/landing.blade.php --}}
@extends('layouts.public')

@section('title', 'LoveLeaf • Օնլայն հարսանեկան հրավիրատոմսեր')

@section('content')
    @if(session('request_success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('request_success') }}
        </div>
    @endif

    {{-- Hero --}}
    <section class="grid gap-8 md:grid-cols-[minmax(0,1.2fr)_minmax(0,1fr)] items-center">
        <div class="text-center md:text-left">
            <p class="text-[11px] sm:text-xs font-semibold uppercase tracking-[0.3em] text-leaf-deep">
                LoveLeaf • Օնլայն հրավիրատոմսեր
            </p>
            <h1 class="mt-3 text-2xl sm:text-3xl md:text-4xl font-display font-semibold tracking-tight text-slate-900">
                Թղթային հրավիրատոմսերի փոխարեն՝
                <span class="text-leaf-deep">գեղեցիկ օնլայն էջ</span>
                ձեր սիրո պատմության համար։
            </h1>
            <p class="mt-4 text-[13px] sm:text-sm leading-relaxed text-slate-700">
                LoveLeaf-ը ձեր հարսանեկան հրավիրատոմսը դարձնում է նուրբ օնլայն էջ՝
                գեղեցիկ դիզայնով, ծրագրով, քարտեզով և հարմար հղումով հյուրերի համար։
                Փոխանակ երկար տեքստեր գրել WhatsApp-ում՝ ուղարկեք մեկ գեղեցիկ հղում։
            </p>

            <div class="mt-5 flex flex-wrap justify-center gap-3 md:justify-start">
                <a
                    href="{{ route('invitation.request.form') }}"
                    class="inline-flex items-center justify-center rounded-full bg-leaf px-5 py-2 text-xs font-medium text-white shadow-lg shadow-leaf/40 hover:bg-leaf-deep"
                >
                    Պատվիրել իմ օնլայն հրավիրատոմսը
                </a>
                <a
                    href="#templates"
                    class="inline-flex items-center justify-center rounded-full border border-slate-300 px-4 py-2 text-xs font-medium text-slate-800 hover:bg-white"
                >
                    Դիտել դիզայնների օրինակները
                </a>
            </div>

            <p class="mt-3 text-[11px] text-slate-500">
                Կարող եք ստեղծել մի քանի տարբեր հրավիրատոմս՝ տարբեր հյուրերի խմբերի համար
                (ընտանիք, ընկերներ, գործընկերներ և այլն)։
            </p>
        </div>

        <div class="relative mt-4 md:mt-0">
            <div class="pointer-events-none absolute -left-8 -top-8 h-32 w-32 rounded-full bg-leaf-soft/60 blur-3xl sm:h-40 sm:w-40"></div>
            <div class="pointer-events-none absolute -right-6 bottom-0 h-28 w-28 rounded-full bg-gold-light/50 blur-3xl sm:h-40 sm:w-40"></div>

            <div class="relative rounded-3xl border border-slate-200/70 bg-white/90 p-4 sm:p-5 shadow-2xl">
                <p class="text-[10px] sm:text-[11px] font-semibold uppercase tracking-[0.25em] text-slate-500">
                    Live preview · LoveLeaf
                </p>
                <div class="mt-3 rounded-2xl border border-gold-light bg-love-blush/60 p-4 sm:p-5">
                    <p class="text-[9px] sm:text-[10px] tracking-[0.35em] uppercase text-slate-500">
                        Օնլայն հրավիրատոմս
                    </p>
                    <h2 class="mt-3 font-script text-xl sm:text-2xl text-leaf-deep">
                        Ռուբեն &amp; Նոննա
                    </h2>
                    <p class="mt-1 text-[10px] sm:text-[11px] tracking-[0.25em] uppercase text-slate-600">
                        16.11.2025 • 18:00
                    </p>
                    <p class="mt-3 text-[12px] sm:text-xs text-slate-700">
                        Restaurant Name, Երևան<br>
                        <span class="text-[11px] text-slate-500">
                            Elegant / Festive dress code
                        </span>
                    </p>
                </div>
                <p class="mt-3 text-[11px] text-slate-500">
                    Յուրաքանչյուր զույգ LoveLeaf-ում ստանում է նմանատիպ էջ՝ իր անուններով, ամսաթվով ու
                    անհատական հղումով։
                </p>
            </div>
        </div>
    </section>

    {{-- Ինչպես է աշխատում --}}
    <section id="how" class="mt-10 sm:mt-12 border-t border-slate-200 pt-8 sm:pt-10">
        <h2 class="text-xs sm:text-sm font-semibold uppercase tracking-[0.25em] text-slate-700 font-display">
            Ինչպես է աշխատում LoveLeaf
        </h2>
        <div class="mt-4 grid gap-4 text-sm text-slate-800 md:grid-cols-3">
            <div class="rounded-2xl bg-white/90 p-4 border border-slate-200/70">
                <p class="text-xs font-semibold text-leaf-deep">Քայլ 1</p>
                <p class="mt-2 font-medium font-display">Ընտրեք դիզայն</p>
                <p class="mt-1 text-[13px] text-slate-700">
                    Ընտրեք Elegant, Nature, Luxury կամ Pastel ոճ՝ ձեր հարսանիքի մթնոլորտին համապատասխան։
                </p>
            </div>
            <div class="rounded-2xl bg-white/90 p-4 border border-slate-200/70">
                <p class="text-xs font-semibold text-leaf-deep">Քայլ 2</p>
                <p class="mt-2 font-medium font-display">Լրացրեք ձեր տվյալները</p>
                <p class="mt-1 text-[13px] text-slate-700">
                    Անուններ, ամսաթիվ, ժամ, վայր, ծրագիր, քարտեզի հղում, dress code և այլ մանրամասներ։
                </p>
            </div>
            <div class="rounded-2xl bg-white/90 p-4 border border-slate-200/70">
                <p class="text-xs font-semibold text-leaf-deep">Քայլ 3</p>
                <p class="mt-2 font-medium font-display">Ուղարկեք հյուրերին</p>
                <p class="mt-1 text-[13px] text-slate-700">
                    Ստանում եք գեղեցիկ հղում, որը կարող եք ուղարկել WhatsApp, Telegram կամ SMS-ով։
                </p>
            </div>
        </div>
    </section>

    {{-- Дизайны --}}
    <section id="templates" class="mt-10 sm:mt-12 border-t border-slate-200 pt-8 sm:pt-10">
        <h2 class="text-xs sm:text-sm font-semibold uppercase tracking-[0.25em] text-slate-700 font-display">
            LoveLeaf դիզայնների հավաքածու
        </h2>
        <p class="mt-2 text-[13px] sm:text-sm text-slate-700">
            Հիմնական 4 ոճ, որոնք կարող են հարմարեցվել ձեր հարսանիքի թեմային։
        </p>

        <div class="mt-5 grid gap-4 md:grid-cols-4 text-[13px]">
            @foreach ($templates as $template)
                <a href="{{ route('demo.show', $template->key) }}"
                   target="_blank"
                   class="{{ $template->card_class }}">
                    <p class="{{ $template->title_class }}">
                        {{ $template->name }}
                    </p>
                    <p class="{{ $template->desc_class }}">
                        {{ $template->description }}
                    </p>
                    <p class="{{ $template->link_class }}">
                        Դիտել կենդանի օրինակը
                    </p>
                </a>
            @endforeach
        </div>

        <p class="mt-3 text-[11px] text-slate-500">
            Հնարավոր է ստեղծել նաև լրիվ անհատական դիզայն՝ ըստ ձեր հարսանիքի կոնցեպտի։
        </p>
    </section>

    {{-- Pricing --}}
    <section id="pricing" class="mt-10 sm:mt-12 border-t border-slate-200 pt-8 sm:pt-10">
        <h2 class="text-xs sm:text-sm font-semibold uppercase tracking-[0.25em] text-slate-700 font-display">
            Գնացուցակ • LoveLeaf փաթեթներ
        </h2>
        <p class="mt-2 text-[13px] sm:text-sm text-slate-700">
            Ընտրեք այն տարբերակը, որը համապատասխանում է ձեր հարսանիքի ձևաչափին և բյուջեին։
        </p>

        <div class="mt-6 grid gap-5 md:grid-cols-3 text-sm">
            {{-- Basic --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                    LoveLeaf Basic
                </p>
                <p class="mt-2 text-lg font-display text-slate-900">
                    Պարզ և նուրբ օնլայն հրավիրատոմս
                </p>

                <p class="mt-3 text-2xl font-semibold text-slate-900">
                    10 000 <span class="text-sm font-normal text-slate-600">AMD</span>
                </p>

                <ul class="mt-4 space-y-1.5 text-[13px] text-slate-700">
                    <li>• 1 դիզայն՝ LoveLeaf հավաքածուից</li>
                    <li>• Միափուլ էջ՝ անուններ, ամսաթիվ, ժամ, վայր</li>
                    <li>• Կարճ շնորհավորական տեքստ</li>
                    <li>• Ծրագիր՝ մինչև 3 կետ</li>
                    <li>• 1 լեզու (HY կամ RU)</li>
                    <li>• 1 փոփոխություն բովանդակության մեջ</li>
                </ul>

                <a
                    href="https://t.me/your_username_here"
                    target="_blank"
                    class="mt-5 inline-flex w-full items-center justify-center rounded-full border border-slate-300 px-4 py-2 text-xs font-medium text-slate-800 hover:bg-white"
                >
                    Պատվիրել Basic Telegram-ով
                </a>
            </div>

            {{-- Comfort --}}
            <div class="relative rounded-2xl border border-leaf-soft bg-white p-5 shadow-md shadow-leaf/10">
                <div class="absolute -top-3 right-4 rounded-full bg-leaf text-[10px] font-medium uppercase tracking-[0.18em] text-white px-3 py-1 shadow-sm">
                    Ամենահայտնին
                </div>

                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-leaf-deep">
                    LoveLeaf Comfort
                </p>
                <p class="mt-2 text-lg font-display text-slate-900">
                    Հարմար տարբերակ՝ ծրագիր, քարտեզ և 2 լեզու
                </p>

                <p class="mt-3 text-2xl font-semibold text-slate-900">
                    20 000 <span class="text-sm font-normal text-slate-600">AMD</span>
                </p>

                <ul class="mt-4 space-y-1.5 text-[13px] text-slate-700">
                    <li>• Ցանկացած դիզայն՝ LoveLeaf հավաքածուից</li>
                    <li>• Անձնական էջ՝ հիմնական տվյալներով</li>
                    <li>• Ծրագիր՝ մինչև 6 կետ</li>
                    <li>• Քարտեզի հղում (Google Maps)</li>
                    <li>• Dress code բաժին</li>
                    <li>• Մինչև 2 լեզու (օր․ HY + RU)</li>
                    <li>• Մինչև 3 փոփոխություն բովանդակության մեջ</li>
                    <li>• Էջը ակտիվ է մինչև 3 ամիս հարսանից հետո</li>
                </ul>

                <a
                    href="https://t.me/your_username_here"
                    target="_blank"
                    class="mt-5 inline-flex w-full items-center justify-center rounded-full bg-leaf px-4 py-2 text-xs font-medium text-white shadow-sm shadow-leaf/40 hover:bg-leaf-deep"
                >
                    Պատվիրել Comfort Telegram-ով
                </a>
            </div>

            {{-- Premium --}}
            <div class="rounded-2xl border border-gold-light bg-love-blush/60 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gold-dark">
                    LoveLeaf Premium
                </p>
                <p class="mt-2 text-lg font-display text-slate-900">
                    Անհատականացված լուծում ձեր հարսանիքի համար
                </p>

                <p class="mt-3 text-2xl font-semibold text-slate-900">
                    35 000 <span class="text-sm font-normal text-slate-600">AMD</span>
                </p>

                <ul class="mt-4 space-y-1.5 text-[13px] text-slate-700">
                    <li>• Անհատականացված կառուցվածք և գունային լուծում</li>
                    <li>• Լրացուցիչ բաժին՝ «Մեր պատմությունը»</li>
                    <li>• Բաժին՝ ծնողների / քավորների մասին</li>
                    <li>• Հատուկ նշումներ՝ նվերների, ծաղիկների, children-free և այլն</li>
                    <li>• Մինչև 3 լեզու</li>
                    <li>• Փոքր փոփոխություններ՝ մինչև հարսանիքի օրը</li>
                    <li>• Խորհրդատվություն WhatsApp / Telegram-ով</li>
                </ul>

                <a
                    href="https://t.me/your_username_here"
                    target="_blank"
                    class="mt-5 inline-flex w-full items-center justify-center rounded-full border border-gold-dark px-4 py-2 text-xs font-medium text-slate-900 hover:bg-white/80"
                >
                    Քննարկել Premium Telegram-ով
                </a>
            </div>
        </div>

        <p class="mt-3 text-[11px] text-slate-500">
            * Գները նախնական են․ կարող են ճշգրտվել՝ կախված ձեր հարսանիքի բարդությունից և լրացուցիչ պահանջներից։
        </p>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="mt-10 sm:mt-12 border-t border-slate-200 pt-8 sm:pt-10">
        <h2 class="text-xs sm:text-sm font-semibold uppercase tracking-[0.25em] text-slate-700 font-display">
            Հաճախ տրվող հարցեր
        </h2>
        <p class="mt-2 text-[13px] sm:text-sm text-slate-700">
            Մի քանի սթանդարտ հարց, որոնք զույգերը տալիս են LoveLeaf օնլայն հրավիրատոմս պատվիրելիս։
        </p>

        <div class="mt-5 grid gap-4 md:grid-cols-2 text-sm">
            <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-4">
                <p class="text-xs font-semibold text-leaf-deep">
                    Ինչպե՞ս եմ ես տալիս տվյալները հրավիրատոմսի համար։
                </p>
                <p class="mt-1 text-[13px] text-slate-700">
                    Պարզ է․ դուք գրում եք մեզ Telegram-ով կամ WhatsApp-ով, ուղարկում եք անունները, ամսաթիվը, վայրը,
                    ծրագիրը և այլ մանրամասներ, իսկ մենք լրացնում ենք LoveLeaf հրավիրատոմսը ձեր փոխարեն։
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-4">
                <p class="text-xs font-semibold text-leaf-deep">
                    Կարո՞ղ ենք փոփոխել տեքստը, եթե ինչ-որ բան փոխվի։
                </p>
                <p class="mt-1 text-[13px] text-slate-700">
                    Այո․ յուրաքանչյուր փաթեթում ներառված է որոշակի քանակի փոփոխություն (Basic՝ 1, Comfort՝ 3, Premium՝ ավելի
                    ճկուն)․ օրինակ՝ ժամի փոփոխություն, փոքր շեշտադրումներ և այլն։
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-4">
                <p class="text-xs font-semibold text-leaf-deep">
                    Որքա՞ն ժամանակով է ակտիվ մնում մեր հրավիրատոմսը։
                </p>
                <p class="mt-1 text-[13px] text-slate-700">
                    Սովորաբար էջը մնում է ակտիվ մինչև հարսանիքի օրը, իսկ Comfort և Premium փաթեթների դեպքում՝
                    մինչև 3 ամիս հարսանից հետո, որպեսզի հյուրերը կարողանան հիշել օրը և տեսնել լింకը նաև հետո։
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-4">
                <p class="text-xs font-semibold text-leaf-deep">
                    Կարո՞ղ ենք ունենալ հրավիրատոմս մի քանի լեզվով։
                </p>
                <p class="mt-1 text-[13px] text-slate-700">
                    Այո․ Comfort փաթեթով կարելի է ունենալ մինչև 2 լեզու (օրինակ՝ HY + RU), իսկ Premium տարբերակով՝ մինչև 3 լեզու։
                    Մենք կօգնենք ձեզ ճիշտ ձևակերպել տեքստերը։
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-4 md:col-span-2">
                <p class="text-xs font-semibold text-leaf-deep">
                    Ինչպե՞ս ենք վճարում և երբ ենք ստանում վերջնական լինկը։
                </p>
                <p class="mt-1 text-[13px] text-slate-700">
                    Վճարումը կարող է լինել բանկային փոխանցմամբ կամ քարտով (կարող ես գրել Telegram-ով՝ մանրամասների համար)։
                    Սովորաբար առաջին տարբերակը ստանում եք 1–2 օրվա ընթացքում, իսկ վերջնական լինկը՝ ձեր հաստատումից հետո։
                </p>
            </div>
        </div>
    </section>
@endsection
