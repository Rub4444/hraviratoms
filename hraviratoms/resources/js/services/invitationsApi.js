// resources/js/services/invitationsApi.js
import { getCsrfToken } from '../utils/meta'

const JSON_HEADERS = {
    Accept: 'application/json',
}

export async function fetchInvitations() {
    const res = await fetch('/api/invitations', {
        headers: JSON_HEADERS,
    });

    if (res.status === 403) {
        return { data: [], meta: null, error: null };
    }

    if (!res.ok) {
        return { data: [], meta: null, error: 'Не удалось загрузить приглашения' };
    }

    const json = await res.json();

    return {
        data: json.data || [],   // ВАЖНО!!!
        meta: json.meta || null,
        error: null,
    };
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
