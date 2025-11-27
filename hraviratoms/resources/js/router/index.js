import { createRouter, createWebHistory } from 'vue-router';

const TemplatesListView = () => import('../views/TemplatesListView.vue');
const InvitationsListView = () => import('../views/InvitationsListView.vue');
const InvitationFormView = () => import('../views/InvitationFormView.vue');

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
];

const router = createRouter({
    history: createWebHistory('/admin/'),
    routes,
});

export default router;
