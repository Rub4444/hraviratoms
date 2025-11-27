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
        @endif>
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
</div>
</body>
</html>

