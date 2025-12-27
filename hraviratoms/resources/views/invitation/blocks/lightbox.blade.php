{{-- invitation/blocks/lightbox.blade.php --}}
<div
    id="lightbox"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 transition-opacity duration-200"
>
    <button
        id="lightbox-close"
        class="absolute top-4 right-4 text-white text-2xl leading-none"
    >
        ✕
    </button>

    <img
        id="lightbox-image"
        src=""
        class="max-h-[90vh] max-w-[90vw] rounded-xl object-contain"
    >
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const lightbox = document.getElementById('lightbox')
    const img = document.getElementById('lightbox-image')
    const closeBtn = document.getElementById('lightbox-close')

    if (!lightbox || !img) return

    document.querySelectorAll('[data-src]').forEach(el => {
        el.addEventListener('click', () => {
            openLightbox(el.dataset.src)
        })
    })

    window.openLightbox = function (src) {
        img.src = src
        lightbox.classList.remove('hidden')
        lightbox.classList.add('flex')
        document.body.style.overflow = 'hidden'
    }

    function closeLightbox() {
        img.src = ''
        lightbox.classList.add('hidden')
        lightbox.classList.remove('flex')
        document.body.style.overflow = ''
    }

    closeBtn?.addEventListener('click', closeLightbox)

    lightbox.addEventListener('click', e => {
        if (e.target === lightbox) closeLightbox()
    })

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeLightbox()
    })
})
</script>
@endpush
{{-- invitation/blocks/lightbox.blade.php --}}
