// resources/js/services/templatesApi.js

export async function fetchTemplates() {
    try {
        const res = await fetch('/api/templates', {
            headers: {
                Accept: 'application/json',
            },
        })

        if (res.status === 403) {
            return { templates: [], error: 'Forbidden' }
        }

        if (!res.ok) {
            return { templates: [], error: 'Не удалось загрузить шаблоны' }
        }

        const data = await res.json()
        return { templates: data, error: '' }
    } catch (e) {
        if (import.meta.env.DEV) {
            console.error('fetchTemplates error', e)
        }
        return { templates: [], error: 'Ошибка при загрузке шаблонов' }
    }
}

export async function deleteTemplateApi(id) {
    const csrf =
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content') || ''

    const res = await fetch(`/api/templates/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrf,
            Accept: 'application/json',
        },
    })

    if (!res.ok) {
        throw new Error('Failed to delete template')
    }

    return true
}
