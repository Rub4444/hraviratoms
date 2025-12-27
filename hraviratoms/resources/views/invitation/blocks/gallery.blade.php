{{-- invitation/blocks/gallery.blade.php --}}
@if(($features['gallery'] ?? false) && !empty($invitation->data['gallery']))

<div class="border-t border-slate-200/70 px-6 py-6">
    <h2 class="mb-4 text-center text-xs font-semibold tracking-[0.25em] uppercase text-slate-500">
        Մեր հիշողությունները
    </h2>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
        @foreach($invitation->data['gallery'] as $index => $img)
            <button
                type="button"
                class="group relative block overflow-hidden rounded-xl"
                data-lightbox
                data-index="{{ $index }}"
                data-src="{{ asset('storage/'.$img['path']) }}"
            >
                <img
                    src="{{ asset('storage/'.$img['path']) }}"
                    class="h-40 w-full object-cover transition duration-300 group-hover:scale-105"
                    loading="lazy"
                >

                <div class="absolute inset-0 bg-black/0 transition group-hover:bg-black/10"></div>
            </button>
        @endforeach
    </div>
</div>

@endif
