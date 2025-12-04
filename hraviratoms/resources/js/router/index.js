// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router'

const TemplatesListView = () => import('../views/TemplatesListView.vue')
const InvitationsListView = () => import('../views/InvitationsListView.vue')
const InvitationFormView = () => import('../views/InvitationFormView.vue')
const InvitationRsvpListView = () => import('../views/InvitationRsvpListView.vue')
const UsersListView = () => import('../views/UsersListView.vue')
const ForbiddenView = () => import('../views/ForbiddenView.vue')

const routes = [
    {
        path: '/',
        name: 'templates.index',
        component: TemplatesListView,
        meta: { requiresSuperadmin: true },
    },
    {
        path: '/invitations',
        name: 'invitations.index',
        component: InvitationsListView,
    },
    {
        path: '/invitations/new/:templateKey',
        name: 'invitations.new',
        component: InvitationFormView,
        props: true,
    },
    {
        path: '/invitations/:id/edit',
        name: 'invitations.edit',
        component: InvitationFormView,
        props: true,
    },
    {
        path: '/invitations/:id/rsvps',
        name: 'invitations.rsvps',
        component: InvitationRsvpListView,
        props: true,
    },
    {
        path: '/users',
        name: 'users.index',
        component: UsersListView,
        meta: { requiresSuperadmin: true },
    },
    {
        path: '/forbidden',
        name: 'forbidden',
        component: ForbiddenView,
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: { name: 'templates.index' },
    },
]

const router = createRouter({
    history: createWebHistory('/admin/'),
    routes,
    scrollBehavior() {
        return { top: 0 }
    },
})

router.beforeEach((to, from, next) => {
    const requiresSuperadmin = to.meta.requiresSuperadmin

    if (!requiresSuperadmin) {
        return next()
    }

    const meta = document.querySelector('meta[name="superadmin"]')
    const isSuperAdmin = meta?.getAttribute('content') === '1'

    if (requiresSuperadmin && !isSuperAdmin) {
        return next({ name: 'forbidden' })
    }

    return next()
})

export default router
