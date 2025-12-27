document.addEventListener('DOMContentLoaded', () => {
    const lightbox = document.getElementById('lightbox')
    const image = document.getElementById('lightbox-image')
    const closeBtn = document.getElementById('lightbox-close')
    const prevBtn = document.getElementById('lightbox-prev')
    const nextBtn = document.getElementById('lightbox-next')

    const items = Array.from(document.querySelectorAll('[data-lightbox]'))
    if (!lightbox || !image || !items.length) return

    const images = items.map(el => el.dataset.src)
    let currentIndex = 0

    function open(index) {
        currentIndex = index
        image.loading = 'eager'
        image.src = images[currentIndex]
        lightbox.classList.remove('hidden')
        lightbox.classList.add('flex')
        document.body.style.overflow = 'hidden'

        preloadNeighbours()
    }

    function close() {
        image.src = ''
        currentIndex = 0
        lightbox.classList.add('hidden')
        lightbox.classList.remove('flex')
        document.body.style.overflow = ''
    }

    function prev() {
        currentIndex = (currentIndex - 1 + images.length) % images.length
        image.loading = 'eager'
        image.src = images[currentIndex]
        preloadNeighbours()
    }

    function next() {
        currentIndex = (currentIndex + 1) % images.length
        image.loading = 'eager'
        image.src = images[currentIndex]
        preloadNeighbours()
    }

    closeBtn?.addEventListener('click', close)
    prevBtn?.addEventListener('click', prev)
    nextBtn?.addEventListener('click', next)

    lightbox.addEventListener('click', e => {
        if (e.target === lightbox) close()
    })

    document.addEventListener('keydown', e => {
        if (lightbox.classList.contains('hidden')) return

        if (e.key === 'Escape') close()
        if (e.key === 'ArrowLeft') prev()
        if (e.key === 'ArrowRight') next()
    })

    let touchStartX = 0
    let touchEndX = 0
    const SWIPE_THRESHOLD = 50

    let isSwiping = false

    lightbox.addEventListener('touchstart', e => {
        isSwiping = true
        touchStartX = e.changedTouches[0].screenX
    }, { passive: true })

    lightbox.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX
        handleSwipe()
        setTimeout(() => isSwiping = false, 50)
    }, { passive: true })

    items.forEach(el => {
        el.addEventListener('click', e => {
            if (isSwiping) return
            open(Number(el.dataset.index))
        })
    })


    function handleSwipe() {
        const diff = touchEndX - touchStartX

        if (Math.abs(diff) < SWIPE_THRESHOLD) return

        if (diff > 0) {
            prev()   // 👉 свайп вправо
        } else {
            next()   // 👉 свайп влево
        }
    }

    function preload(index) {
        if (!images[index]) return

        const img = new Image()
        img.src = images[index]
    }

    function preloadNeighbours() {
        preload((currentIndex + 1) % images.length)
        preload((currentIndex - 1 + images.length) % images.length)
    }

})
