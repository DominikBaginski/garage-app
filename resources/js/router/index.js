import { createRouter, createWebHistory } from 'vue-router';

import Dashboard from '../components/Dashboard.vue';
import Garages from '../components/garages/Index.vue';
import Bookings from '../components/bookings/Index.vue';

const routes = [
    {
        path: '/dashboard',
        component: Dashboard
    },
    {
        path: '/garages',
        component: Garages
    },
    {
        path: '/bookings',
        component: Bookings
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
