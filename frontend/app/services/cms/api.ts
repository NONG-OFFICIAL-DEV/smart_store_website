import axios from 'axios'
import { useLoadingStore } from '~/stores/loadingStore'
import type { ApiError } from '~/types/api'

const cmsApi = axios.create({
  headers: {
    'Content-Type': 'application/json'
  }
})

cmsApi.interceptors.request.use(requestConfig => {
  const runtimeConfig = useRuntimeConfig()
  requestConfig.baseURL = runtimeConfig.public.cmsApiUrl || 'http://127.0.0.1:8000/api/v1'

  // Public resources resolve translatable fields server-side from a
  // `locale` query param (defaulting to English if absent) — attach the
  // site's current locale to every request so CMS content actually follows
  // the language switcher instead of always rendering English. Read via
  // useNuxtApp().$i18n rather than useI18n() — the latter requires an
  // active component instance (it's a Vue inject() under the hood), which
  // this interceptor doesn't have when it fires; $i18n is the same locale
  // ref exposed on the Nuxt app instance instead, safe to read anywhere.
  const locale = useNuxtApp().$i18n.locale.value
  requestConfig.params = { ...requestConfig.params, locale }

  const token = import.meta.client ? localStorage.getItem('cms_admin_token') : null
  if (token) {
    requestConfig.headers.Authorization = `Bearer ${token}`
  }

  const loaderType = requestConfig.meta?.loader ?? 'skip'
  if (loaderType !== 'skip') {
    useLoadingStore().start(loaderType)
    requestConfig.meta = { ...requestConfig.meta, __loadingStarted: true }
  }

  return requestConfig
})

// Laravel error payloads look like { message, errors }. Unwrap so callers
// can use `err.message` directly.
cmsApi.interceptors.response.use(
  response => {
    if (response.config.meta?.__loadingStarted) useLoadingStore().stop()
    return response
  },
  error => {
    if (error.config?.meta?.__loadingStarted) useLoadingStore().stop()

    const message = error.response?.data?.message || error.message
    const wrapped = new Error(message) as ApiError
    wrapped.status = error.response?.status
    wrapped.errors = error.response?.data?.errors
    return Promise.reject(wrapped)
  }
)

export default cmsApi
