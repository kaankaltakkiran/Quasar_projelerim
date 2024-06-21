import { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') },
      { path: '/payment', component: () => import('pages/PaymentPage.vue') },
      {
        path: '/calendar',
        component: () => import('components/CalendarComponent.vue'),
      },
      {
        path: '/persons',
        component: () => import('pages/PersonPage.vue'),
      },
      {
        path: '/locations',
        component: () => import('components/LocationComponent.vue'),
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
];

export default routes;
