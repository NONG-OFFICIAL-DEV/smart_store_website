<template>
  <div>
    <h1 class="page-title">Site Content</h1>
    <p class="page-sub">Edit the homepage hero, About page, and footer.</p>

    <Alert v-if="error" variant="destructive" class="mb-4">
      <AlertDescription>{{ error }}</AlertDescription>
    </Alert>

    <InlineLoader v-if="loading" min-height="120px" />

    <template v-else>
      <div class="language-row">
        <Label class="language-label">Editing language</Label>
        <Tabs v-model="activeLocale">
          <TabsList>
            <TabsTrigger v-for="loc in LOCALES" :key="loc.code" :value="loc.code">
              {{ loc.label }}
            </TabsTrigger>
          </TabsList>
        </Tabs>
      </div>

      <Tabs v-model="tab" class="w-full">
        <TabsList class="mb-6">
          <TabsTrigger value="hero">Hero</TabsTrigger>
          <TabsTrigger value="about">About</TabsTrigger>
          <TabsTrigger value="footer">Footer</TabsTrigger>
        </TabsList>

      <!-- ── Hero ── -->
      <TabsContent value="hero">
        <section class="editor-section">
          <Row dense>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Badge text</Label>
                <Input v-model="htr.badge_text" />
              </div>
            </Col>
            <Col cols="12" sm="6" />
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Headline</Label>
                <Input v-model="htr.headline" />
              </div>
            </Col>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Subheadline</Label>
                <Input v-model="htr.subheadline" />
              </div>
            </Col>
            <Col cols="12">
              <div class="field">
                <Label>Description</Label>
                <Textarea v-model="htr.description" rows="2" />
              </div>
            </Col>
            <Col cols="12">
              <div class="field">
                <Label>Trust line</Label>
                <Input v-model="htr.trust_line" />
              </div>
            </Col>
            <Col cols="12" sm="4">
              <div class="field">
                <Label>Primary CTA label</Label>
                <Input v-model="htr.cta_primary_label" />
              </div>
            </Col>
            <Col cols="12" sm="4">
              <div class="field">
                <Label>Secondary CTA label</Label>
                <Input v-model="htr.cta_secondary_label" />
              </div>
            </Col>
            <Col cols="12" sm="4">
              <div class="field">
                <Label>Secondary CTA URL</Label>
                <Input v-model="heroData.cta_secondary_url" />
              </div>
            </Col>
          </Row>

          <h3 class="sub-heading">Stats</h3>
          <div v-for="(s, i) in htr.stats" :key="i" class="repeat-row">
            <Input v-model="s.num" placeholder="Value" />
            <Input v-model="s.label" placeholder="Label" />
            <Button size="icon-sm" variant="ghost" @click="htr.stats.splice(i, 1)">
              <Icon name="mdi-close" size="16" />
            </Button>
          </div>
          <Button size="sm" variant="secondary" @click="htr.stats.push({ num: '', label: '' })">
            <Icon name="mdi-plus" size="16" />
            Add stat
          </Button>

          <div class="save-row">
            <Button :disabled="saving" @click="saveHero">
              <Icon v-if="saving" name="mdi-loading" size="16" class="animate-spin" />
              Save
            </Button>
          </div>
        </section>
      </TabsContent>

      <!-- ── About ── -->
      <TabsContent value="about">
        <section class="editor-section">
          <h3 class="sub-heading sub-heading--first">Hero</h3>
          <Row dense>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Eyebrow tag</Label>
                <Input v-model="atr.hero_tag" />
              </div>
            </Col>
            <Col cols="12" sm="6" />
            <Col cols="12">
              <div class="field">
                <Label>Heading</Label>
                <Input v-model="atr.hero_heading" />
              </div>
            </Col>
            <Col cols="12">
              <div class="field">
                <Label>Description</Label>
                <Textarea v-model="atr.hero_description" rows="2" />
              </div>
            </Col>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Primary CTA label</Label>
                <Input v-model="atr.hero_cta_primary_label" />
              </div>
            </Col>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Secondary CTA label</Label>
                <Input v-model="atr.hero_cta_secondary_label" />
              </div>
            </Col>
          </Row>

          <h3 class="sub-heading">Story ("Why I build these products")</h3>
          <Row dense>
            <Col cols="12">
              <div class="field">
                <Label>Title</Label>
                <Input v-model="atr.story_title" />
              </div>
            </Col>
            <Col cols="12">
              <div class="field">
                <Label>Content (separate paragraphs with a blank line)</Label>
                <Textarea v-model="atr.story_content" rows="4" />
              </div>
            </Col>
          </Row>

          <h3 class="sub-heading">What I Build (section intro — products themselves come from the Products page)</h3>
          <Row dense>
            <Col cols="12" sm="4">
              <div class="field">
                <Label>Eyebrow tag</Label>
                <Input v-model="atr.products_tag" />
              </div>
            </Col>
            <Col cols="12" sm="4">
              <div class="field">
                <Label>Title</Label>
                <Input v-model="atr.products_title" />
              </div>
            </Col>
            <Col cols="12" sm="4">
              <div class="field">
                <Label>Description</Label>
                <Input v-model="atr.products_description" />
              </div>
            </Col>
          </Row>

          <h3 class="sub-heading">Approach ("How I build" — 4 cards)</h3>
          <Row dense>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Eyebrow tag</Label>
                <Input v-model="atr.approach_tag" />
              </div>
            </Col>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Title</Label>
                <Input v-model="atr.approach_title" />
              </div>
            </Col>
          </Row>
          <div v-for="(c, i) in atr.approach_cards" :key="i" class="repeat-row repeat-row--wide">
            <Input v-model="c.icon" placeholder="Icon (mdi-...)" />
            <Input v-model="c.title" placeholder="Title" />
            <Input v-model="c.description" placeholder="Description" />
            <Button size="icon-sm" variant="ghost" @click="atr.approach_cards.splice(i, 1)">
              <Icon name="mdi-close" size="16" />
            </Button>
          </div>
          <Button
            size="sm"
            variant="secondary"
            @click="atr.approach_cards.push({ icon: 'mdi-check-circle-outline', title: '', description: '' })"
          >
            <Icon name="mdi-plus" size="16" />
            Add card
          </Button>

          <h3 class="sub-heading">Who I Build For</h3>
          <Row dense>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Eyebrow tag</Label>
                <Input v-model="atr.audience_tag" />
              </div>
            </Col>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Title</Label>
                <Input v-model="atr.audience_title" />
              </div>
            </Col>
            <Col cols="12">
              <div class="field">
                <Label>Description</Label>
                <Input v-model="atr.audience_description" />
              </div>
            </Col>
          </Row>
          <div v-for="(ex, i) in atr.audience_examples" :key="i" class="nested-card nested-card--shot">
            <img v-if="ex.image_url" :src="ex.image_url" class="shot-preview" :alt="ex.label" />
            <Row dense class="grow">
              <Col cols="12" sm="6" md="3">
                <div class="field">
                  <Label>Label</Label>
                  <Input v-model="ex.label" />
                </div>
              </Col>
              <Col cols="12" sm="6" md="3">
                <div class="field">
                  <Label>Fallback icon (mdi-...)</Label>
                  <Input v-model="ex.icon" />
                </div>
              </Col>
              <Col cols="12" md="6">
                <div class="field">
                  <Label>Description</Label>
                  <Input v-model="ex.description" />
                </div>
              </Col>
              <Col cols="12" sm="6">
                <div class="field">
                  <Label>Image URL</Label>
                  <Input v-model="ex.image_url" placeholder="https://..." />
                </div>
              </Col>
              <Col cols="12" sm="6">
                <div class="field">
                  <Label>Or upload an image</Label>
                  <FileInput
                    accept="image/*"
                    :loading="uploadingAudienceImage === i"
                    @change="(e: Event) => handleAudienceImageUpload(e, i)"
                  />
                </div>
              </Col>
              <Col cols="12" class="flex items-center justify-between">
                <label class="featured-toggle">
                  <Switch v-model="ex.featured" />
                  Featured
                </label>
                <Button
                  type="button"
                  size="icon-sm"
                  variant="ghost"
                  class="text-destructive hover:text-destructive"
                  @click="atr.audience_examples.splice(i, 1)"
                >
                  <Icon name="mdi-delete-outline" size="16" />
                </Button>
              </Col>
            </Row>
          </div>
          <p v-if="!atr.audience_examples.length" class="nested-empty">No examples yet.</p>
          <Button
            size="sm"
            variant="secondary"
            @click="
              atr.audience_examples.push({ icon: 'mdi-store-outline', label: '', description: '', image_url: '', featured: false })
            "
          >
            <Icon name="mdi-plus" size="16" />
            Add example
          </Button>

          <h3 class="sub-heading">Personal Profile (all fields optional — hidden on the page when blank)</h3>
          <Row dense>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Photo URL</Label>
                <Input v-model="aboutData.profile_photo_url" />
              </div>
            </Col>
            <Col cols="12" sm="6">
              <FileInput label="Upload photo" accept="image/*" :loading="uploadingPhoto" @change="handlePhotoUpload" />
            </Col>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Greeting (e.g. Hi, I'm Nong.)</Label>
                <Input v-model="atr.profile_greeting" />
              </div>
            </Col>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Name</Label>
                <Input v-model="atr.profile_name" />
              </div>
            </Col>
            <Col cols="12">
              <div class="field">
                <Label>Bio</Label>
                <Textarea v-model="atr.profile_bio" rows="3" />
              </div>
            </Col>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Contact email</Label>
                <Input v-model="aboutData.email" />
              </div>
            </Col>
          </Row>

          <h4 class="sub-heading sub-heading--sm">Skills</h4>
          <div v-for="(skill, i) in atr.profile_skills" :key="i" class="repeat-row repeat-row--skill">
            <Input v-model="atr.profile_skills[i]" placeholder="Skill" />
            <Button size="icon-sm" variant="ghost" @click="atr.profile_skills.splice(i, 1)">
              <Icon name="mdi-close" size="16" />
            </Button>
          </div>
          <Button size="sm" variant="secondary" @click="atr.profile_skills.push('')">
            <Icon name="mdi-plus" size="16" />
            Add skill
          </Button>

          <h4 class="sub-heading sub-heading--sm">Social links (GitHub, LinkedIn, etc.)</h4>
          <p class="hint">
            Names "Telegram" / "Facebook" / "TikTok" / "GitHub" / "LinkedIn" get their matching icon automatically;
            anything else gets a generic link icon.
          </p>
          <div v-for="(s, i) in aboutData.socials" :key="i" class="repeat-row">
            <Input v-model="s.name" placeholder="Name" />
            <Input v-model="s.href" placeholder="URL" />
            <Button size="icon-sm" variant="ghost" @click="aboutData.socials.splice(i, 1)">
              <Icon name="mdi-close" size="16" />
            </Button>
          </div>
          <Button size="sm" variant="secondary" @click="aboutData.socials.push({ name: '', href: '' })">
            <Icon name="mdi-plus" size="16" />
            Add social link
          </Button>

          <h3 class="sub-heading">CTA</h3>
          <Row dense>
            <Col cols="12">
              <div class="field">
                <Label>Heading</Label>
                <Input v-model="atr.cta_title" />
              </div>
            </Col>
            <Col cols="12">
              <div class="field">
                <Label>Description</Label>
                <Textarea v-model="atr.cta_description" rows="2" />
              </div>
            </Col>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Primary CTA label</Label>
                <Input v-model="atr.cta_primary_label" />
              </div>
            </Col>
            <Col cols="12" sm="6">
              <div class="field">
                <Label>Secondary CTA label</Label>
                <Input v-model="atr.cta_secondary_label" />
              </div>
            </Col>
          </Row>

          <div class="save-row">
            <Button :disabled="saving" @click="saveAbout">
              <Icon v-if="saving" name="mdi-loading" size="16" class="animate-spin" />
              Save
            </Button>
          </div>
        </section>
      </TabsContent>

      <!-- ── Footer ── -->
      <TabsContent value="footer">
        <section class="editor-section">
          <Row dense>
            <Col cols="12" sm="4">
              <div class="field">
                <Label>Email</Label>
                <Input v-model="footerData.email" />
              </div>
            </Col>
            <Col cols="12" sm="4">
              <div class="field">
                <Label>Phone</Label>
                <Input v-model="footerData.phone" />
              </div>
            </Col>
            <Col cols="12" sm="4">
              <div class="field">
                <Label>Address</Label>
                <Input v-model="ftr.address" />
              </div>
            </Col>
          </Row>

          <h3 class="sub-heading">Social links</h3>
          <p class="hint">Names "Telegram" / "Facebook" / "TikTok" get their matching icon automatically; anything else gets a generic link icon.</p>
          <div v-for="(s, i) in footerData.socials" :key="i" class="repeat-row">
            <Input v-model="s.name" placeholder="Name" />
            <Input v-model="s.href" placeholder="URL" />
            <Button size="icon-sm" variant="ghost" @click="footerData.socials.splice(i, 1)">
              <Icon name="mdi-close" size="16" />
            </Button>
          </div>
          <Button size="sm" variant="secondary" @click="footerData.socials.push({ name: '', href: '' })">
            <Icon name="mdi-plus" size="16" />
            Add social link
          </Button>

          <div class="save-row">
            <Button :disabled="saving" @click="saveFooter">
              <Icon v-if="saving" name="mdi-loading" size="16" class="animate-spin" />
              Save
            </Button>
          </div>
        </section>
      </TabsContent>
      </Tabs>
    </template>
  </div>
</template>

<script setup lang="ts">
  definePageMeta({ layout: 'admin' })

  import { Alert, AlertDescription } from '~/components/ui/alert'
  import { Button } from '~/components/ui/button'
  import { Input } from '~/components/ui/input'
  import { Label } from '~/components/ui/label'
  import { Switch } from '~/components/ui/switch'
  import { Tabs, TabsContent, TabsList, TabsTrigger } from '~/components/ui/tabs'
  import { Textarea } from '~/components/ui/textarea'
  import {
    getHeroForEdit,
    updateHeroForLocale,
    getAboutForEdit,
    updateAboutForLocale,
    getFooterForEdit,
    updateFooterForLocale,
    DATA_KEYS
  } from '~/services/cms/siteContent'
  import { uploadProductMedia } from '~/services/cms/adminProducts'
  import { FALLBACK_HERO, FALLBACK_ABOUT, FALLBACK_FOOTER } from '~/stores/siteContent'

  const notify = useNotif()
  const tab = ref('hero')
  const loading = ref(true)
  const saving = ref(false)
  const uploadingPhoto = ref(false)
  const uploadingAudienceImage = ref<number | null>(null)
  const error = ref<string | null>(null)

  const LOCALES = [
    { code: 'en', label: 'English' },
    { code: 'km', label: 'Khmer' }
  ]
  const activeLocale = ref('en')

  // Splits a flat fallback object (data + content merged, matching the old
  // single-locale shape) back into its two halves using the same DATA_KEYS
  // the backend/service already use — so the initial "en" translation shows
  // the site's real starting copy instead of blank fields, exactly like
  // before locale tabs existed.
  function splitFallback(key: string, fallback: Record<string, any>) {
    const dataKeys = DATA_KEYS[key] ?? []
    const data: Record<string, any> = {}
    const content: Record<string, any> = {}
    for (const [k, v] of Object.entries(fallback)) {
      if (k === 'id') continue
      ;(dataKeys.includes(k) ? data : content)[k] = v
    }
    return { data, content }
  }
  const HERO_FALLBACK = splitFallback('hero', FALLBACK_HERO)
  const ABOUT_FALLBACK = splitFallback('about', FALLBACK_ABOUT)
  const FOOTER_FALLBACK = splitFallback('footer', FALLBACK_FOOTER)

  // ── Non-translatable "data" halves (same across every locale) ──
  const heroData = reactive<Record<string, any>>({ cta_secondary_url: '', ...HERO_FALLBACK.data })
  const aboutData = reactive<Record<string, any>>({
    email: '',
    profile_photo_url: '',
    socials: [] as { name: string; href: string }[],
    ...ABOUT_FALLBACK.data
  })
  const footerData = reactive<Record<string, any>>({
    email: '',
    phone: '',
    socials: [] as { name: string; href: string }[],
    ...FOOTER_FALLBACK.data
  })

  // ── Translatable "content" halves, one object per locale ──
  function blankHeroTr() {
    return {
      badge_text: '',
      headline: '',
      subheadline: '',
      description: '',
      trust_line: '',
      cta_primary_label: '',
      cta_secondary_label: '',
      stats: [] as { num: string; label: string }[]
    }
  }
  function blankAboutTr() {
    return {
      hero_tag: '',
      hero_heading: '',
      hero_description: '',
      hero_cta_primary_label: '',
      hero_cta_secondary_label: '',
      story_title: '',
      story_content: '',
      products_tag: '',
      products_title: '',
      products_description: '',
      approach_tag: '',
      approach_title: '',
      approach_cards: [] as { icon: string; title: string; description: string }[],
      audience_tag: '',
      audience_title: '',
      audience_description: '',
      audience_examples: [] as { icon: string; label: string; description: string; image_url: string; featured: boolean }[],
      profile_greeting: '',
      profile_name: '',
      profile_bio: '',
      profile_skills: [] as string[],
      cta_title: '',
      cta_description: '',
      cta_primary_label: '',
      cta_secondary_label: ''
    }
  }
  function blankFooterTr() {
    return { address: '' }
  }

  const heroTranslationsByLocale = reactive<Record<string, ReturnType<typeof blankHeroTr>>>(
    Object.fromEntries(LOCALES.map((l) => [l.code, l.code === 'en' ? { ...blankHeroTr(), ...HERO_FALLBACK.content } : blankHeroTr()]))
  )
  const aboutTranslationsByLocale = reactive<Record<string, ReturnType<typeof blankAboutTr>>>(
    Object.fromEntries(LOCALES.map((l) => [l.code, l.code === 'en' ? { ...blankAboutTr(), ...ABOUT_FALLBACK.content } : blankAboutTr()]))
  )
  const footerTranslationsByLocale = reactive<Record<string, ReturnType<typeof blankFooterTr>>>(
    Object.fromEntries(LOCALES.map((l) => [l.code, l.code === 'en' ? { ...blankFooterTr(), ...FOOTER_FALLBACK.content } : blankFooterTr()]))
  )

  const htr = computed(() => heroTranslationsByLocale[activeLocale.value]!)
  const atr = computed(() => aboutTranslationsByLocale[activeLocale.value]!)
  const ftr = computed(() => footerTranslationsByLocale[activeLocale.value]!)

  onMounted(async () => {
    loading.value = true
    error.value = null
    try {
      const [hero, about, footer] = await Promise.all([getHeroForEdit(), getAboutForEdit(), getFooterForEdit()])

      // For "en" specifically, always layer the real fallback content
      // underneath whatever's actually saved — not just when no row exists
      // at all. A translation row can already exist but simply predate a
      // newly-added field (like this session's `audience_tag`), in which
      // case it should still show its real starting value here instead of
      // blank, exactly like the public site already does via its own
      // fallback-merge in stores/siteContent.ts.
      if (hero) {
        Object.assign(heroData, hero.data)
        for (const loc of LOCALES) {
          const found = hero.translations.find((t) => t.locale === loc.code)
          const base = loc.code === 'en' ? { ...blankHeroTr(), ...HERO_FALLBACK.content } : blankHeroTr()
          heroTranslationsByLocale[loc.code] = found ? { ...base, ...found } : base
        }
      }
      if (about) {
        Object.assign(aboutData, about.data)
        for (const loc of LOCALES) {
          const found = about.translations.find((t) => t.locale === loc.code)
          const base = loc.code === 'en' ? { ...blankAboutTr(), ...ABOUT_FALLBACK.content } : blankAboutTr()
          aboutTranslationsByLocale[loc.code] = found ? { ...base, ...found } : base
        }
      }
      if (footer) {
        Object.assign(footerData, footer.data)
        for (const loc of LOCALES) {
          const found = footer.translations.find((t) => t.locale === loc.code)
          const base = loc.code === 'en' ? { ...blankFooterTr(), ...FOOTER_FALLBACK.content } : blankFooterTr()
          footerTranslationsByLocale[loc.code] = found ? { ...base, ...found } : base
        }
      }
    } catch (err: any) {
      error.value = err.message
    } finally {
      loading.value = false
    }
  })

  async function saveHero() {
    saving.value = true
    error.value = null
    try {
      await updateHeroForLocale(heroData, htr.value, activeLocale.value)
      notify('Hero section saved', { type: 'success' })
    } catch (err: any) {
      notify(err.message || 'Failed to save hero section', { type: 'error' })
    } finally {
      saving.value = false
    }
  }

  async function handlePhotoUpload(e: Event) {
    const file = (e.target as HTMLInputElement)?.files?.[0]
    if (!file) return
    uploadingPhoto.value = true
    try {
      aboutData.profile_photo_url = await uploadProductMedia(file)
    } catch (err: any) {
      notify(err.message || 'Failed to upload photo', { type: 'error' })
    } finally {
      uploadingPhoto.value = false
    }
  }

  async function handleAudienceImageUpload(e: Event, i: number) {
    const file = (e.target as HTMLInputElement)?.files?.[0]
    if (!file) return
    uploadingAudienceImage.value = i
    try {
      atr.value.audience_examples[i]!.image_url = await uploadProductMedia(file)
    } catch (err: any) {
      notify(err.message || 'Failed to upload image', { type: 'error' })
    } finally {
      uploadingAudienceImage.value = null
    }
  }

  async function saveAbout() {
    saving.value = true
    error.value = null
    try {
      await updateAboutForLocale(aboutData, atr.value, activeLocale.value)
      notify('About page saved', { type: 'success' })
    } catch (err: any) {
      notify(err.message || 'Failed to save About page', { type: 'error' })
    } finally {
      saving.value = false
    }
  }

  async function saveFooter() {
    saving.value = true
    error.value = null
    try {
      await updateFooterForLocale(footerData, ftr.value, activeLocale.value)
      notify('Footer saved', { type: 'success' })
    } catch (err: any) {
      notify(err.message || 'Failed to save footer', { type: 'error' })
    } finally {
      saving.value = false
    }
  }
</script>

<style scoped>
  .page-title {
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0 0 4px;
  }
  .page-sub {
    font-size: 0.86rem;
    color: color-mix(in srgb, var(--foreground) 60%, transparent);
    margin: 0 0 24px;
  }
  .language-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid color-mix(in srgb, var(--foreground) 8%, transparent);
  }
  .language-label {
    font-size: 0.82rem;
    font-weight: 700;
    color: color-mix(in srgb, var(--foreground) 60%, transparent);
  }
  .field {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .field-error {
    font-size: 0.75rem;
    color: var(--destructive);
    margin: 0;
  }

  .editor-section {
    padding: 24px;
    border: 1px solid color-mix(in srgb, var(--foreground) 8%, transparent);
    border-radius: 14px;
    background: color-mix(in srgb, var(--card) 60%, transparent);
  }

  .sub-heading {
    font-size: 0.9rem;
    font-weight: 800;
    margin: 24px 0 12px;
  }
  .sub-heading--first {
    margin-top: 0;
  }
  .sub-heading--sm {
    font-size: 0.82rem;
    margin: 18px 0 10px;
  }
  .hint {
    font-size: 0.8rem;
    color: color-mix(in srgb, var(--foreground) 55%, transparent);
    margin: 0 0 12px;
  }

  .repeat-row {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 8px;
    align-items: center;
    margin-bottom: 6px;
  }
  .repeat-row--wide {
    grid-template-columns: 1fr 1fr 1.4fr auto;
  }
  .repeat-row--skill {
    grid-template-columns: 1fr auto;
  }
  .featured-toggle {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    white-space: nowrap;
  }

  .nested-card {
    padding: 14px 16px;
    border: 1px solid color-mix(in srgb, var(--foreground) 7%, transparent);
    border-radius: 10px;
    margin-bottom: 10px;
  }
  .nested-card--shot {
    display: flex;
    gap: 14px;
    align-items: flex-start;
  }
  .shot-preview {
    width: 96px;
    height: 64px;
    object-fit: cover;
    border-radius: 8px;
    flex-shrink: 0;
  }
  .nested-empty {
    font-size: 0.85rem;
    color: color-mix(in srgb, var(--foreground) 50%, transparent);
    margin: 0 0 10px;
  }

  .save-row {
    margin-top: 24px;
  }

  @media (max-width: 640px) {
    .repeat-row,
    .repeat-row--wide,
    .repeat-row--skill {
      grid-template-columns: 1fr;
    }
  }
</style>
