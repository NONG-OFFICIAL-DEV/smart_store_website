import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'FristLoginForm',
    component: () => import('@/views/UnderContruction.vue')
  },
  // {
  //   path: '/layout',
  //   component: () => import('@/views/layout/Layout.vue'),
  //   children: [
  //     {
  //       path: '/admin-dashboard',
  //       name: 'Admin Dashboard',
  //       component: () => import('@/views/admin/AdminHome.vue'),
  //       meta: { requiresAuth: true }
  //     }
  //   ]
  // }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})


export default router
