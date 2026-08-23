// app/composables/useLanguageSwitcher.ts
interface LanguageOption {
  code: string
  label: string
  imgSrc: string
  alt: string
}

export function useLanguageSwitcher() {
  const { t, locale, setLocale } = useI18n()

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

  async function selectLang(code: string) {
    if (code === locale.value) {
      menuOpen.value = false
      return
    }
    // setLocale() (not locale.value = code) — with lazy-loaded locale
    // messages, direct assignment skips the async import that fetches the
    // target locale's JSON, leaving every t() call unresolved (raw keys).
    await setLocale(code as Parameters<typeof setLocale>[0])
    menuOpen.value = false

    // setLocale() only re-resolves the static UI strings (nav labels,
    // buttons, i18n/locales/*.json) — every CMS-backed store (products,
    // solutions, testimonials, site content, etc.) fetches once and caches,
    // so none of that content re-queries with the new locale on its own.
    // A full reload is the simplest reliable fix: @nuxtjs/i18n already
    // persists the choice via the `i18n_redirected` cookie, so the fresh
    // SSR response comes back correctly localized end-to-end.
    if (import.meta.client) window.location.reload()
  }

  return { menuOpen, languages, currentLang, selectLang, locale }
}
