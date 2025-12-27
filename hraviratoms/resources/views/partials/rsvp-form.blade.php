{{-- resources/views/partials/rsvp-form.blade.php --}}

<form
    method="POST"
    action="{{ isset($invitation->slug) ? route('invitation.public.rsvp', $invitation->slug) : '#' }}"
    class="space-y-3"
    @if($preview ?? false)
        onsubmit="return false"
    @endif
>
    @csrf

    <input
        type="text"
        name="name"
        placeholder="Ձեր անունը"
        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"
        @if($preview ?? false) disabled @endif
        required
    >

    <select
        name="status"
        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"
        @if($preview ?? false) disabled @endif
        required
    >
        <option value="">Ընտրեք</option>
        <option value="yes">Կգամ</option>
        <option value="no">Չեմ գա</option>
        <option value="maybe">Դեռ չգիտեմ</option>
    </select>

    <textarea
        name="comment"
        rows="2"
        placeholder="Մեկնաբանություն (ըստ ցանկության)"
        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"
        @if($preview ?? false) disabled @endif
    ></textarea>

    @if($preview ?? false)
        <button
            type="button"
            disabled
            class="w-full rounded-xl bg-slate-300 px-4 py-2 text-xs font-semibold text-slate-600 cursor-not-allowed"
        >
            RSVP (preview)
        </button>
    @else
        <button
            type="submit"
            class="w-full rounded-xl bg-rose-500 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-600"
        >
            Ուղարկել
        </button>
    @endif
</form>
