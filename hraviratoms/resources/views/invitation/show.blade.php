<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>{{ $invitation->meta_title ?? ($invitation->bride_name.' & '.$invitation->groom_name) }}</title>
    <meta name="description" content="{{ $invitation->meta_description ?? '' }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/invitation-page.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Dancing+Script:wght@400;600&family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">

    @php
        /*
        |--------------------------------------------------------------------------
        | DESIGN (template → invitation override)
        |--------------------------------------------------------------------------
        */
        $templateDesign = $invitation->template->config['design'] ?? [];
        $customDesign   = $invitation->data['design'] ?? [];
        $design = array_replace_recursive($templateDesign, $customDesign);

        /*
        |--------------------------------------------------------------------------
        | FEATURES (template → invitation override)
        |--------------------------------------------------------------------------
        */
        $templateFeatures = $invitation->template->config['features'] ?? [];
        $customFeatures   = $invitation->data['features'] ?? [];
        $features = array_replace_recursive($templateFeatures, $customFeatures);
    @endphp
</head>

<body
    class="invite-page theme-{{ $design['theme'] ?? $invitation->template->key }}"
    style="
        --color-primary: {{ $design['colors']['primary'] ?? '#1E293B' }};
        --color-accent: {{ $design['colors']['accent'] ?? '#94A3B8' }};
        --color-bg: {{ $design['colors']['background'] ?? '#ffffff' }};
        --font-heading: '{{ $design['fonts']['heading'] ?? 'Playfair Display' }}';
        --font-body: '{{ $design['fonts']['body'] ?? 'Inter' }}';
    "
>

<div class="invite-card">

    {{-- ================= HEADER ================= --}}
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

    {{-- ================= PROGRAM ================= --}}
    @if(($features['program'] ?? false) && !empty($invitation->data['program']))
        <div class="border-t border-slate-200/70 px-6 py-5">
            <h2 class="mb-3 text-xs font-semibold tracking-[0.25em] uppercase text-slate-500">
                Օրվա ծրագիրը
            </h2>

            <ul class="space-y-2 text-sm">
                @foreach($invitation->data['program'] as $item)
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

    {{-- ================= FOOTER TEXT ================= --}}
    <div class="border-t border-slate-200/70 px-6 py-5 text-center">
        <p class="text-xs text-slate-500">
            Սիրով հրավիրում ենք Ձեզ կիսելու մեր հատուկ օրը։
        </p>
        <p class="mt-3 text-[11px] text-slate-400">
            {{ $invitation->bride_name }} &amp; {{ $invitation->groom_name }}
        </p>
    </div>

    {{-- ================= RSVP ================= --}}
    @if($features['rsvp'] ?? false)
        <div class="border-t border-slate-200/70 px-6 py-6">
            <h2 class="mb-3 text-xs font-semibold tracking-[0.25em] uppercase text-slate-500">
                Հաստատեք մասնակցությունը
            </h2>

            @if(session('rsvp_success'))
                <div class="mb-3 rounded-xl px-4 py-2 text-xs"
                     style="background: color-mix(in srgb, var(--color-accent) 20%, white); color: var(--color-primary);">
                    Շնորհակալություն պատասխանի համար 🕊️ Ձեր տվյալները պահպանված են։
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('invitation.public.rsvp', $invitation->slug) }}"
                class="grid gap-3 text-left md:grid-cols-2"
            >
                @csrf

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-medium text-slate-600">
                        Անուն, Ազգանուն
                    </label>
                    <input required name="guest_name"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-600">
                        Հեռախոս
                    </label>
                    <input name="guest_phone"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-600">
                        Քանի՞ մարդ
                    </label>
                    <input type="number" name="guests_count" min="1" max="20" value="1"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-medium text-slate-600">
                        Մասակցության կարգավիճակ
                    </label>
                    <div class="mt-1 flex gap-4 text-[11px]">
                        <label><input type="radio" name="status" value="yes" checked> Կգամ</label>
                        <label><input type="radio" name="status" value="maybe"> Հնարավոր է</label>
                        <label><input type="radio" name="status" value="no"> Չեմ կարող</label>
                    </div>
                </div>

                <div class="md:col-span-2 text-right">
                    <button type="submit" class="btn-primary">
                        Ուղարկել պատասխաններս
                    </button>
                </div>
            </form>
        </div>
    @endif

</div>
</body>
</html>
