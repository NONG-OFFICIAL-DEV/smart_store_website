// app/composables/useLanguageSwitcher.ts
interface LanguageOption {
  code: string
  label: string
  imgSrc: string
  alt: string
}

export function useLanguageSwitcher() {
  const { t, locale } = useI18n()
  const switchLocalePath = useSwitchLocalePath()

  const menuOpen = ref(false)

  const languages = computed<LanguageOption[]>(() => [
    {
      code: 'km',
      label: t('common.lang_km') || 'ខ្មែរ',
      imgSrc: 'https://flagcdn.com/w80/kh.png',
      alt: 'Khmer'
    },
    {
      code: 'en',
      label: t('common.lang_en') || 'English',
      imgSrc: 'https://flagcdn.com/w80/gb.png',
      alt: 'English'
    }
  ])

  const currentLang = computed(() => languages.value.find((l) => l.code === locale.value) ?? languages.value[0]!)

  function selectLang(code: string) {
    if (code === locale.value) {
      menuOpen.value = false
      return
    }
    menuOpen.value = false

    // Hard navigation (not client-side router) to that locale's own URL
    // (e.g. /about -> /km/about) — every CMS-backed store (products,
    // solutions, testimonials, site content, etc.) fetches once and caches,
    // so a client-side-only locale change wouldn't re-query any of that
    // content in the new language. Landing on the target URL via a full
    // reload guarantees a correctly localized SSR response end-to-end, and
    // @nuxtjs/i18n persists the choice via the `i18n_redirected` cookie for
    // next time.
    if (import.meta.client) window.location.href = switchLocalePath(code)
  }

  return { menuOpen, languages, currentLang, selectLang, locale }
}
