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
const { t, locale } = useI18n()
const route = useRoute()
const switchLocalePath = useSwitchLocalePath()

// Mirrors `site.url`/`i18n.baseUrl` in nuxt.config.ts — kept as a plain
// constant here since there's no site-config composable already wired up
// in this app to read it from.
const siteUrl = 'https://www.nexstacktech.com'
const ogImageUrl = `${siteUrl}/og/website.png`

// Global fallback — per-page useSeoMeta() calls (see products/[slug].vue,
// blog/[slug].vue, etc.) override title/description with page-specific
// copy; this only applies where a page doesn't set its own. `locale`/`t()`
// now come straight from the matched route (English at /about, Khmer at
// /km/about — see nuxt.config.ts's `i18n.strategy`), so a link-preview
// crawler fetching either URL gets that URL's own correct-language OG tags
// with no cookies/JS required.
useSeoMeta({
  title: () => t('meta.title'),
  description: () => t('meta.description'),

  ogTitle: () => t('meta.og_title'),
  ogDescription: () => t('meta.description'),
  ogType: 'website',
  ogUrl: () => `${siteUrl}${route.path}`,
  ogImage: ogImageUrl,
  ogImageWidth: '1900',
  ogImageHeight: '902',

  twitterCard: 'summary_large_image',
  twitterTitle: () => t('meta.og_title'),
  twitterDescription: () => t('meta.description'),
  twitterImage: ogImageUrl,
})

// Sets <html class="dark"> during SSR itself (cookie is readable on the
// server, unlike localStorage) — see useColorMode.ts for why this replaces
// Vuetify's useTheme(). htmlAttrs.lang/dir and the hreflang <link
// rel="alternate"> tags come from useLocaleHead(), driven by the matched
// route's own locale — no manual lang-detection logic needed here anymore.
const { isDark } = useColorMode()
const i18nHead = useLocaleHead()
useHead(() => ({
  htmlAttrs: {
    ...i18nHead.value.htmlAttrs,
    class: isDark.value ? 'dark' : ''
  },
  link: [...(i18nHead.value.link || [])],
  meta: [...(i18nHead.value.meta || [])]
}))

// Keyboard switcher
const handleKeyDown = (e: KeyboardEvent) => {
  if (e.shiftKey && e.ctrlKey && e.key === 'L') {
    e.preventDefault()
    const newLocale = locale.value === 'en' ? 'km' : 'en'
    // Hard navigation to the target locale's own URL — see
    // useLanguageSwitcher.ts's selectLang() for why (CMS-backed stores
    // fetch once and cache, so a client-side-only change wouldn't
    // re-resolve them; landing on that locale's real URL via a full
    // reload guarantees a correctly localized SSR response end-to-end).
    window.location.href = switchLocalePath(newLocale)
  }
}

onMounted(() => {
  const app = instance.appContext.app

  // Register global methods for both Options and Composition APIs
  app.config.globalProperties.$notif = notifRef.value?.newAlert
  app.config.globalProperties.$confirm = confirmRef.value?.open

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
