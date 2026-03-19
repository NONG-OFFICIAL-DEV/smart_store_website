import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'FristLoginForm',
    component: () => import('@/views/UnderContruction.vue')
  },
  {
    path: '/emenu',
    name: 'EMenuLanding',
    component: () => import('@/views/EMenuLanding.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})


export default router
