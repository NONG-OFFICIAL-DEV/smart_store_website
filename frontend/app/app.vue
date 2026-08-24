<template>
  <div>
    <Notif ref="notifRef" dismissible :default-timeout="2000" />
    <Confirm ref="confirmRef" />
    <NuxtLoadingIndicator color="var(--primary)" />
    <NuxtLayout>
      <NuxtPage />
    </NuxtLayout>
    <Loading />
  </div>
</template>

<script setup lang="ts">

import Notif from '~/components/global/Notification.vue'
import Confirm from '~/components/global/Confirm.vue'
import Loading from '~/components/global/Loading.vue'

const notifRef = ref<InstanceType<typeof Notif> | null>(null)
const confirmRef = ref<InstanceType<typeof Confirm> | null>(null)

const instance = getCurrentInstance()!
const { t, locale, setLocale } = useI18n()
const route = useRoute()

// Mirrors `site.url` in nuxt.config.ts — kept as a plain constant here since
// there's no site-config composable already wired up in this app to read it from.
const siteUrl = 'https://www.nexstacktech.com'
// No dedicated 1200x630 OG banner exists yet — icon.png is the closest real
// asset (near-square, so it survives social platforms' auto-cropping better
// than the wide wordmark logo would). Swap this for a purpose-built banner
// image later if one gets designed.
const ogImageUrl = `${siteUrl}/icon.png`

// Global fallback — per-page useSeoMeta() calls (see products/[slug].vue,
// blog/[slug].vue, etc.) override title/description with page-specific
// copy; this only applies where a page doesn't set its own.
useSeoMeta({
  title: () => t('meta.title'),
  description: () => t('meta.description'),

  ogTitle: () => t('meta.og_title'),
  ogDescription: () => t('meta.description'),
  ogType: 'website',
  ogUrl: () => `${siteUrl}${route.path}`,
  ogImage: ogImageUrl,
  ogImageWidth: '1069',
  ogImageHeight: '1069',

  twitterCard: 'summary_large_image',
  twitterTitle: () => t('meta.og_title'),
  twitterDescription: () => t('meta.description'),
  twitterImage: ogImageUrl,
})
// Sets <html class="dark"> during SSR itself (cookie is readable on the
// server, unlike localStorage) — see useColorMode.ts for why this replaces
// Vuetify's useTheme().
const { isDark } = useColorMode()
useHead({
  htmlAttrs: {
    class: computed(() => (isDark.value ? 'dark' : ''))
  }
})

// Keyboard switcher
const handleKeyDown = (e: KeyboardEvent) => {
  if (e.shiftKey && e.ctrlKey && e.key === 'L') {
    e.preventDefault()
    const newLocale = locale.value === 'en' ? 'km' : 'en'
    // setLocale() (not locale.value = ...) — required to trigger the
    // lazy-loaded locale messages import; see useLanguageSwitcher.ts.
    setLocale(newLocale)
    localStorage.setItem('lang', newLocale)
  }
}

onMounted(() => {
  const app = instance.appContext.app

  // Register global methods for both Options and Composition APIs
  app.config.globalProperties.$notif = notifRef.value?.newAlert
  app.config.globalProperties.$confirm = confirmRef.value?.open

  // Restore saved language preference
  const savedLang = localStorage.getItem('lang')
  if (savedLang === 'en' || savedLang === 'km') {
    setLocale(savedLang)
  }

  // Bind keyboard event
  document.addEventListener('keydown', handleKeyDown)
})

onBeforeUnmount(() => {
  document.removeEventListener('keydown', handleKeyDown)
})
</script>

<style>
html, body {
  overflow-y: auto !important;
  /* Clips horizontal bleed from decorative absolutely-positioned elements
     (hero graphics, etc.) at the true top-level scroll container. This
     used to live on .landing-main (a flex item inside .landing-layout),
     but per the CSS Overflow spec, setting overflow-x to anything other
     than 'visible' forces the computed overflow-y to 'auto' too — even
     if overflow-y is explicitly written as 'visible' — which turned that
     flex item into its own independent scroll container stacked on top
     of this one, i.e. two scrollbars. body has no such flex-parent
     interaction, so it's safe here.
  */
  overflow-x: hidden;
}
</style>
