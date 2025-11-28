<template>
  <section>
    <div class="mb-4 flex items-center justify-between gap-2">
      <div>
        <h1 class="text-2xl font-semibold">Users</h1>
        <p class="mt-1 text-sm text-slate-600">
          Управление аккаунтами и правами супер-админа.
        </p>
      </div>
      <button
        class="btn-primary"
        type="button"
        @click="startCreate"
      >
        + New user
      </button>
    </div>

    <!-- список -->
    <div
      v-if="loading"
      class="rounded-2xl border bg-white p-4 text-sm text-slate-500 shadow-sm"
    >
      Loading users...
    </div>

    <div
      v-else-if="!users.length"
      class="rounded-2xl border bg-white p-4 text-sm text-slate-500 shadow-sm"
    >
      Пока нет ни одного пользователя.
    </div>

    <div
      v-else
      class="overflow-hidden rounded-2xl border bg-white shadow-sm"
    >
      <table class="min-w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
          <tr>
            <th class="px-4 py-3">Name</th>
            <th class="px-4 py-3">Email</th>
            <th class="px-4 py-3">Role</th>
            <th class="px-4 py-3">Invitations</th>
            <th class="px-4 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="user in users"
            :key="user.id"
            class="border-t last:border-b hover:bg-slate-50/60"
          >
            <td class="px-4 py-3 align-top">
              <div class="font-medium text-slate-900">
                {{ user.name }}
              </div>
            </td>
            <td class="px-4 py-3 align-top text-xs text-slate-600">
              {{ user.email }}
            </td>
            <td class="px-4 py-3 align-top text-xs">
              <span
                v-if="user.is_superadmin"
                class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700"
              >
                Superadmin
              </span>
              <span
                v-else
                class="inline-flex items-center rounded-full bg-slate-50 px-2 py-0.5 text-[11px] font-medium text-slate-500"
              >
                User
              </span>
            </td>
            <td class="px-4 py-3 align-top text-xs text-slate-600">
              {{ user.invitations_count ?? 0 }}
            </td>
            <td class="px-4 py-3 align-top text-right text-xs">
              <div class="flex flex-wrap justify-end gap-2">
                <button
                  class="rounded-full border border-slate-200 px-3 py-1 font-medium hover:bg-slate-50"
                  type="button"
                  @click="startEdit(user)"
                >
                  Edit
                </button>
                <button
                  class="rounded-full bg-red-500 px-3 py-1 font-medium text-white hover:bg-red-600"
                  type="button"
                  @click="deleteUser(user)"
                >
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <p v-if="error" class="mt-3 text-xs text-red-500">
      {{ error }}
    </p>

    <!-- форма создания/редактирования -->
    <div
      v-if="showForm"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
    >
      <div class="w-full max-w-md rounded-2xl bg-white p-5 shadow-xl">
        <h2 class="mb-3 text-lg font-semibold">
          {{ editingUser ? 'Edit user' : 'Create user' }}
        </h2>

        <form class="space-y-3" @submit.prevent="submitForm">
          <div>
            <label class="block text-xs font-medium text-slate-700">
              Name
            </label>
            <input
              v-model="form.name"
              type="text"
              class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-leaf focus:ring-leaf"
              required
            />
          </div>

          <div>
            <label class="block text-xs font-medium text-slate-700">
              Email
            </label>
            <input
              v-model="form.email"
              type="email"
              class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-leaf focus:ring-leaf"
              required
            />
          </div>

          <div>
            <label class="block text-xs font-medium text-slate-700">
              Password
            </label>
            <input
              v-model="form.password"
              :placeholder="editingUser ? 'Оставьте пустым, чтобы не менять' : ''"
              type="password"
              class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-leaf focus:ring-leaf"
              :required="!editingUser"
            />
          </div>

          <label class="inline-flex items-center gap-2 text-xs text-slate-700">
            <input
              v-model="form.is_superadmin"
              type="checkbox"
              class="rounded border-slate-300 text-leaf focus:ring-leaf"
            />
            <span>Superadmin</span>
          </label>

          <div class="mt-4 flex justify-end gap-2">
            <button
              type="button"
              class="rounded-full border border-slate-200 px-4 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50"
              @click="closeForm"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="rounded-full bg-leaf px-4 py-1.5 text-xs font-medium text-white hover:bg-leaf-deep"
              :disabled="formSubmitting"
            >
              {{ formSubmitting ? 'Saving...' : 'Save' }}
            </button>
          </div>

          <p v-if="formError" class="mt-2 text-xs text-red-500">
            {{ formError }}
          </p>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const users = ref([]);
const loading = ref(true);
const error = ref('');

const showForm = ref(false);
const editingUser = ref(null);
const form = ref({
  name: '',
  email: '',
  password: '',
  is_superadmin: false,
});
const formSubmitting = ref(false);
const formError = ref('');

// csrf
const csrfToken =
  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

const loadUsers = async () => {
  loading.value = true;
  error.value = '';

  try {
    const res = await fetch('/api/users');
    if (!res.ok) throw new Error('Failed to load users');

    users.value = await res.json();
  } catch (e) {
    console.error(e);
    error.value = e.message || 'Error loading users';
  } finally {
    loading.value = false;
  }
};

const startCreate = () => {
  editingUser.value = null;
  form.value = {
    name: '',
    email: '',
    password: '',
    is_superadmin: false,
  };
  formError.value = '';
  showForm.value = true;
};

const startEdit = (user) => {
  editingUser.value = user;
  form.value = {
    name: user.name,
    email: user.email,
    password: '',
    is_superadmin: !!user.is_superadmin,
  };
  formError.value = '';
  showForm.value = true;
};

const closeForm = () => {
  showForm.value = false;
};

const submitForm = async () => {
  formSubmitting.value = true;
  formError.value = '';

  try {
    const payload = { ...form.value };

    const url = editingUser.value
      ? `/api/users/${editingUser.value.id}`
      : '/api/users';

    const method = editingUser.value ? 'PUT' : 'POST';

    const res = await fetch(url, {
      method,
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify(payload),
    });

    if (!res.ok) {
      console.error('Save user error status:', res.status);
      throw new Error('Failed to save user');
    }

    await loadUsers();
    showForm.value = false;
  } catch (e) {
    console.error(e);
    formError.value = e.message || 'Error saving user';
  } finally {
    formSubmitting.value = false;
  }
};

const deleteUser = async (user) => {
  if (!confirm(`Удалить пользователя "${user.name}" (${user.email})?`)) {
    return;
  }

  const deleteInvitations = confirm(
    'Также удалить все его приглашения? Нажмите "OK" — удалить вместе с приглашениями, "Cancel" — только отвязать приглашения.'
  );

  try {
    const res = await fetch(`/api/users/${user.id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({
        delete_invitations: deleteInvitations,
      }),
    });

    if (!res.ok) {
      console.error('Delete user error status:', res.status);
      throw new Error('Failed to delete user');
    }

    await loadUsers();
  } catch (e) {
    console.error(e);
    alert(e.message || 'Error deleting user');
  }
};

onMounted(loadUsers);
</script>
