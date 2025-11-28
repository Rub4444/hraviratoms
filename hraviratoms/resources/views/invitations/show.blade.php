<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>{{ $invitation->meta_title ?? ($invitation->bride_name.' & '.$invitation->groom_name) }}</title>
    <meta name="description" content="{{ $invitation->meta_description ?? '' }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite('resources/js/app.js')


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Dancing+Script:wght@400;600&family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
</head>
<body class="invite-page theme-{{ $invitation->template->key }} antialiased">
<div class="invite-card">
    <div class="px-6 pt-8 pb-6 text-center">
        <p class="text-[10px] tracking-[0.35em] uppercase text-slate-400">
            Օնլայն հրավիրատոմս
        </p>

        <h1 class="invite-names mt-3">
            {{ $invitation->bride_name }} &amp; {{ $invitation->groom_name }}
        </h1>

        @if($invitation->date)
            <p class="mt-3 text-xs tracking-[0.25em] uppercase invite-accent">
                {{ $invitation->date->format('d.m.Y') }}
                @if($invitation->time)
                    • {{ $invitation->time }}
                @endif
            </p>
        @endif

        @if($invitation->venue_name || $invitation->venue_address)
            <p class="mt-3 text-sm text-slate-600">
                {{ $invitation->venue_name }}<br>
                <span class="text-xs text-slate-500">
                    {{ $invitation->venue_address }}
                </span>
            </p>
        @endif

        @if($invitation->dress_code)
            <p class="mt-2 text-xs uppercase tracking-[0.25em] text-slate-500">
                Dress code: {{ $invitation->dress_code }}
            </p>
        @endif
    </div>

    @php
        $program = $invitation->data['program'] ?? null;
    @endphp

    @if(is_array($program) && count($program))
        <div class="border-t border-white/30 border-slate-200/70 px-6 py-5">
            <h2 class="mb-3 text-xs font-semibold tracking-[0.25em] uppercase text-slate-500">
                Օրվա ծրագիրը
            </h2>
            <ul class="space-y-2 text-sm">
                @foreach($program as $item)
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 w-16 text-xs font-semibold invite-accent">
                            {{ $item['time'] ?? '' }}
                        </span>
                        <span class="text-slate-700">
                            {{ $item['label'] ?? '' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

        <div class="border-t border-white/30 border-slate-200/70 px-6 py-5 text-center">
        <p class="text-xs text-slate-500">
            Սիրով հրավիրում ենք Ձեզ կիսելու մեր հատուկ օրը։
        </p>
        <p class="mt-3 text-[11px] text-slate-400">
            {{ $invitation->bride_name }} &amp; {{ $invitation->groom_name }}
        </p>
    </div>

    {{-- RSVP блок --}}
    <div class="border-t border-white/30 border-slate-200/70 px-6 py-6">
        <h2 class="mb-3 text-xs font-semibold tracking-[0.25em] uppercase text-slate-500">
            Հաստատեք մասնակցությունը
        </h2>

        @if(session('rsvp_success'))
            <div class="mb-3 rounded-xl bg-leaf-soft/20 px-4 py-2 text-xs text-leaf-deep">
                Շնորհակալություն պատասխանի համար 🕊️ Ձեր տվյալները պահպանված են։
            </div>
        @endif

        <form method="POST" action="{{ route('invitation.public.rsvp', $invitation->slug) }}" class="grid gap-3 text-left md:grid-cols-2">
            @csrf

            <div class="md:col-span-2">
                <label class="block text-[11px] font-medium text-slate-600">
                    Անուն, Ազգանուն
                </label>
                <input
                    type="text"
                    name="guest_name"
                    value="{{ old('guest_name') }}"
                    required
                    class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-leaf focus:ring-leaf"
                >
                @error('guest_name')
                    <p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-600">
                    Հեռախոս (ըստ ցանկության)
                </label>
                <input
                    type="text"
                    name="guest_phone"
                    value="{{ old('guest_phone') }}"
                    class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-leaf focus:ring-leaf"
                >
                @error('guest_phone')
                    <p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-600">
                    Քանի՞ մարդ կմասնակցի
                </label>
                <input
                    type="number"
                    name="guests_count"
                    min="1"
                    max="20"
                    value="{{ old('guests_count', 1) }}"
                    class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-leaf focus:ring-leaf"
                >
                @error('guests_count')
                    <p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-600">
                    Մասակցության կարգավիճակ
                </label>
                <div class="mt-1 flex gap-2 text-[11px] text-slate-700">
                    <label class="inline-flex items-center gap-1">
                        <input
                            type="radio"
                            name="status"
                            value="yes"
                            class="h-3 w-3 rounded border-slate-300 text-leaf focus:ring-leaf"
                            {{ old('status', 'yes') === 'yes' ? 'checked' : '' }}
                        >
                        Կգամ
                    </label>
                    <label class="inline-flex items-center gap-1">
                        <input
                            type="radio"
                            name="status"
                            value="maybe"
                            class="h-3 w-3 rounded border-slate-300 text-leaf focus:ring-leaf"
                            {{ old('status') === 'maybe' ? 'checked' : '' }}
                        >
                        Հնարավոր է
                    </label>
                    <label class="inline-flex items-center gap-1">
                        <input
                            type="radio"
                            name="status"
                            value="no"
                            class="h-3 w-3 rounded border-slate-300 text-leaf focus:ring-leaf"
                            {{ old('status') === 'no' ? 'checked' : '' }}
                        >
                        Վերջապես չեմ կարող
                    </label>
                </div>
                @error('status')
                    <p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-[11px] font-medium text-slate-600">
                    Լրացուցիչ տեղեկություն (ըստ ցանկության)
                </label>
                <textarea
                    name="message"
                    rows="3"
                    class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-leaf focus:ring-leaf"
                    placeholder="Օրինակ՝ կունենանք երեխա մեզ հետ, պետք է հատուկ սնունդ, կուշանանք 15 րոպե և այլն։"
                >{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2 flex justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-full bg-leaf px-5 py-2 text-xs font-medium text-white shadow-sm shadow-leaf/40 hover:bg-leaf-deep"
                >
                    Ուղարկել պատասխաններս
                </button>
            </div>
        </form>
        @if(isset($isDemo) && $isDemo)
            <div class="mt-10 text-center">
                <div class="inline-block rounded-2xl border border-leaf-soft bg-white/60 px-6 py-4 shadow-sm backdrop-blur">
                    <p class="text-[13px] text-slate-700 font-medium">
                        Ցանկանու՞մ եք նման օնլայն հրավիրատոմս ձեր հարսանիքի համար։
                    </p>
                    <p class="mt-1 text-[13px] text-slate-600">
                        Կապ հաստատեք մեզ հետ WhatsApp կամ Viber միջոցով․
                    </p>

                    <div class="mt-3 flex justify-center gap-3">
                        <a href="https://wa.me/374XXXXXXXX"
                        class="rounded-full bg-green-500 px-4 py-1.5 text-xs font-medium text-white hover:bg-green-600 transition">
                        WhatsApp
                        </a>

                        <a href="viber://chat?number=374XXXXXXXX"
                        class="rounded-full bg-purple-500 px-4 py-1.5 text-xs font-medium text-white hover:bg-purple-600 transition">
                        Viber
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>


</body>
</html>


