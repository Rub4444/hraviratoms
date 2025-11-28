import { createRouter, createWebHistory } from 'vue-router';

const TemplatesListView = () => import('../views/TemplatesListView.vue');
const InvitationsListView = () => import('../views/InvitationsListView.vue');
const InvitationFormView = () => import('../views/InvitationFormView.vue');
const InvitationRsvpListView = () => import('../views/InvitationRsvpListView.vue');
const UsersListView = () => import('../views/UsersListView.vue');
const ForbiddenView = () => import('../views/ForbiddenView.vue');

const routes = [
    {
        path: '/',
        name: 'templates.index',
        component: TemplatesListView,
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
        meta: { requiresAuth: true },
    },
    {
        path: '/forbidden',
        name: 'forbidden',
        component: () => ForbiddenView,
    },

];

const router = createRouter({
    history: createWebHistory('/admin/'),
    routes,
});

export default router;
