// resources/js/services/invitationsApi.js
import { getCsrfToken } from '../utils/meta'

const JSON_HEADERS = {
    Accept: 'application/json',
}

export async function fetchInvitations() {
    try {
        const res = await fetch('/api/invitations', {
            headers: {
                'Accept': 'application/json',
            },
            credentials: 'include',
        })

        if (!res.ok) {
            throw new Error('Failed to load invitations')
        }

        const json = await res.json()

        // Поддерживаем оба варианта ответа:
        // 1) массив: [ ... ]
        // 2) пагинатор: { data: [...], meta: {...}, ... }
        let data
        let meta = null

        if (Array.isArray(json)) {
            data = json
        } else {
            data = Array.isArray(json.data) ? json.data : []
            meta = json.meta ?? null
        }

        return { data, meta, error: null }
    } catch (e) {
        console.error(e)
        return {
            data: [],
            meta: null,
            error: e.message || 'Error loading invitations',
        }
    }
}






export async function deleteInvitationApi(id) {
    const res = await fetch(`/api/invitations/${id}`, {
        method: 'DELETE',
        headers: {
            ...JSON_HEADERS,
            'X-CSRF-TOKEN': getCsrfToken(),
        },
    })

    if (!res.ok) {
        throw new Error(`Delete failed with status ${res.status}`)
    }

    return true
}

export async function bulkDeleteInvitationsApi(ids = []) {
    const results = []

    for (const id of ids) {
        try {
            await deleteInvitationApi(id)
            results.push({ id, success: true })
        } catch (e) {
            console.warn('Bulk delete error for', id, e)
            results.push({ id, success: false })
        }
    }

    return results
}
