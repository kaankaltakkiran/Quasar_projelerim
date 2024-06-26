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
        component: () => import('pages/CalendarPage.vue'),
      },
      {
        path: '/persons',
        component: () => import('pages/PersonPage.vue'),
      },
      {
        path: '/persons/:userid',
        props: true,
        component: () => import('pages/PersonDetailPage.vue'),
      },
      {
        path: '/locations',
        component: () => import('pages/LocationPage.vue'),
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
