{{-- invitation/blocks/rsvp.blade.php --}}

@php
    /** @var bool $isPreview */
    $isPreview = $preview ?? false;
@endphp

@if($features['rsvp'] ?? false)

<div class="border-t border-slate-200/70 px-6 py-6">
    <h2 class="mb-3 text-xs font-semibold tracking-[0.25em] uppercase text-slate-500">
        Հաստատեք մասնակցությունը
    </h2>

    @if(session('rsvp_error'))
        <div class="mb-3 rounded-xl bg-red-50 px-4 py-2 text-xs text-red-700">
            {{ session('rsvp_error') }}
        </div>
    @endif

    @if(session('rsvp_success') && !$isPreview)
        <div class="mb-3 rounded-xl px-4 py-2 text-xs bg-green-50 text-green-700">
            Շնորհակալություն 🕊️ Ձեր պատասխանը պահպանվել է։
        </div>
    @else

        <form
            method="POST"
            action="{{ $isPreview ? '#' : route('invitation.public.rsvp', $invitation->slug) }}"
            @if($isPreview) onsubmit="return false" @endif
            class="grid gap-3 text-left md:grid-cols-2"
        >
            @csrf

            {{-- NAME --}}
            <div class="md:col-span-2">
                <label class="block text-[11px] font-medium text-slate-600">
                    Անուն, Ազգանուն
                </label>
                <input
                    name="guest_name"
                    @required(!$isPreview)
                    @disabled($isPreview)
                    class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"
                >
            </div>

            {{-- PHONE --}}
            <div>
                <label class="block text-[11px] font-medium text-slate-600">
                    Հեռախոս
                </label>
                <input
                    name="guest_phone"
                    @required(!$isPreview)
                    @disabled($isPreview)
                    class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"
                >
            </div>

            {{-- GUESTS COUNT --}}
            <div>
                <label class="block text-[11px] font-medium text-slate-600">
                    Քանի՞ մարդ
                </label>
                <input
                    type="number"
                    name="guests_count"
                    min="1"
                    max="20"
                    value="1"
                    @required(!$isPreview)
                    @disabled($isPreview)
                    class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"
                >
            </div>

            {{-- STATUS --}}
            <div class="md:col-span-2">
                <label class="block text-[11px] font-medium text-slate-600">
                    Մասակցության կարգավիճակ
                </label>
                <div class="mt-1 flex gap-4 text-[11px]">
                    <label>
                        <input type="radio" name="status" value="yes" checked @disabled($isPreview)>
                        Կգամ
                    </label>
                    <label>
                        <input type="radio" name="status" value="maybe" @disabled($isPreview)>
                        Հնարավոր է
                    </label>
                    <label>
                        <input type="radio" name="status" value="no" @disabled($isPreview)>
                        Չեմ կարող
                    </label>
                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="md:col-span-2 text-right">
                <button
                    type="{{ $isPreview ? 'button' : 'submit' }}"
                    class="btn-primary"
                    @disabled($isPreview)
                >
                    {{ $isPreview ? 'RSVP (preview)' : 'Ուղարկել պատասխաններս' }}
                </button>
            </div>
        </form>
    @endif
</div>

@endif
