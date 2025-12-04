// resources/js/utils/meta.js

export function getCsrfToken() {
    return (
        document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
        ''
    )
}

export function isSuperAdmin() {
    return (
        document
            .querySelector('meta[name="superadmin"]')
            ?.getAttribute('content') === '1'
    )
}
