{{-- invitation/blocks/header.blade.php --}}

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
