<div
    id="lightbox"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 touch-none"
>
    {{-- CLOSE --}}
    <button
        id="lightbox-close"
        class="absolute top-4 right-4 text-white text-2xl"
        type="button"
    >✕</button>

    {{-- LEFT --}}
    <button
        id="lightbox-prev"
        class="absolute left-4 text-white text-3xl"
        type="button"
    >‹</button>

    {{-- IMAGE --}}
    <img
        id="lightbox-image"
        src=""
        class="max-h-[90vh] max-w-[90vw] rounded-xl object-contain"
    >

    {{-- RIGHT --}}
    <button
        id="lightbox-next"
        class="absolute right-4 text-white text-3xl"
        type="button"
    >›</button>
</div>
