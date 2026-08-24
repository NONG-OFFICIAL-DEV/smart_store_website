<template>
  <section class="section-pad pricing-page">
    <Container>
      <!-- ── Header ── -->
      <div class="hub-header text-center" data-aos="fade-up">
        <span class="section-tag">{{ t('pricing_hub.tag') }}</span>
        <h1 class="section-title">{{ t('pricing_hub.title') }}</h1>
        <p class="section-sub hub-sub">{{ t('pricing_hub.sub') }}</p>
      </div>

      <InlineLoader v-if="productsStore.loading" min-height="200px" />

      <template v-else-if="tabs.length">
        <!-- ── Product selector ── -->
        <div class="product-selector-wrap" data-aos="fade-up">
          <Tabs v-model="activeSlug">
            <TabsList class="product-selector">
              <TabsTrigger v-for="p in tabs" :key="p.slug" :value="p.slug">
                {{ p.name }}
              </TabsTrigger>
            </TabsList>
          </Tabs>
        </div>

        <!-- ═══════════════════ Nexstack POS ═══════════════════ -->
        <template v-if="activeSlug === 'nexstack-pos'">
          <!-- Same cycle-toggle + plan cards component used on the
               Nexstack POS product detail page's pricing section. -->
          <PosPricingCards data-aos="fade-up" />

          <!-- ── Feature comparison ── -->
          <ComparisonTable
            v-if="posVisiblePlans.length"
            class="mt-16"
            :title="t('pricing_hub.compare_title', { product: activeProductName })"
            :subtitle="t('pricing_hub.compare_sub')"
            :plans="posVisiblePlans.map((p) => ({ code: p.code, name: p.name }))"
            :rows="posFeatureRows"
          />
        </template>

        <!-- ═══════════════════ Studio Management ═══════════════════ -->
        <template v-else-if="activeSlug === 'studio-management'">
          <div v-if="studioHasPaidPlans" class="cycle-wrap" data-aos="fade-up">
            <div class="cycle-track" role="group" :aria-label="t('common.billing_cycle')">
              <button
                v-for="c in STUDIO_CYCLES"
                :key="c.months"
                class="cycle-btn"
                :class="{ 'cycle-btn--active': selectedMonths === c.months }"
                :aria-pressed="selectedMonths === c.months"
                @click="selectedMonths = c.months"
              >
                <span>{{ t(c.labelKey) }}</span>
                <span v-if="c.months > 1" class="cycle-btn__badge">-{{ studioSavingsPct(c.months) }}%</span>
              </button>
            </div>
          </div>

          <InlineLoader v-if="studioStore.loading" min-height="320px" />
          <Alert
            v-else-if="studioStore.error"
            class="flex items-center gap-2 border-info/30 bg-info/10 text-info mx-auto max-w-[480px]"
          >
            <Icon name="mdi-clock-outline" size="18" />
            <AlertDescription>{{ t('studio_pricing.unavailable') }}</AlertDescription>
          </Alert>

          <template v-else>
            <div class="cards-grid" :data-count="studioStore.plans.length" data-aos="fade-up">
              <div
                v-for="plan in studioStore.plans"
                :key="plan.id"
                class="plan-card"
                :class="{ 'plan-card--featured': plan.code === 'professional' }"
              >
                <Badge
                  v-if="plan.code === 'professional'"
                  class="popular-badge bg-primary text-primary-foreground border-transparent"
                >
                  <Icon name="mdi-star" size="12" />
                  {{ t('common.most_popular') }}
                </Badge>

                <h3 class="plan-name">{{ plan.name }}</h3>
                <p class="plan-desc">{{ plan.description }}</p>

                <div class="price-block">
                  <template v-if="studioIsFree(plan)">
                    <div class="price-row">
                      <span class="price-currency">$</span>
                      <span class="price-amount price-amount--free">0</span>
                    </div>
                    <div class="price-meta">
                      <Badge class="bg-primary/10 text-primary border-transparent">
                        {{ t('studio_pricing.trial_days', { days: plan.trial_days || 14 }) }}
                      </Badge>
                    </div>
                  </template>
                  <template v-else>
                    <div class="price-row">
                      <span class="price-currency">$</span>
                      <span class="price-amount">{{ studioMonthlyPrice(plan) }}</span>
                    </div>
                    <div class="price-meta">
                      <span class="price-per">{{ t('common.per_month') }}</span>
                    </div>
                  </template>
                </div>

                <div class="plan-divider" />

                <ul class="feature-list">
                  <li v-for="f in studioTopFeatures(plan)" :key="f" class="feature-item">
                    <Icon name="mdi-check" size="14" class="feature-check" />
                    <span>{{ f }}</span>
                  </li>
                </ul>

                <Button
                  :variant="plan.code === 'professional' ? 'default' : 'outline'"
                  class="plan-cta w-full"
                  @click="goToStudioRegister(plan.code)"
                >
                  {{ studioIsFree(plan) ? t('button.start_free_trial') : t('button.get_started') }}
                  <Icon name="mdi-arrow-right" size="16" />
                </Button>
              </div>
            </div>

            <ComparisonTable
              class="mt-16"
              :title="t('pricing_hub.compare_title', { product: activeProductName })"
              :subtitle="t('pricing_hub.compare_sub')"
              :plans="studioStore.plans.map((p) => ({ code: p.code, name: p.name }))"
              :rows="studioFeatureRows"
            />
          </template>
        </template>
      </template>
    </Container>
  </section>
</template>

<script setup lang="ts">
  import { Alert, AlertDescription } from '~/components/ui/alert'
  import { Badge } from '~/components/ui/badge'
  import { Button } from '~/components/ui/button'
  import { Tabs, TabsList, TabsTrigger } from '~/components/ui/tabs'
  import ComparisonTable from '~/components/sections/PricingComparisonTable.vue'
  import type { StudioPlan } from '~/types'

  const { t, locale } = useI18n()
  const productsStore = useProductsStore()
  const posStore = usePosPlansStore()
  const studioStore = useStudioPlansStore()

  // Only products with a real, distinct pricing dataset get a tab here —
  // every other CMS product (if any are added later with no pricing
  // component of their own yet) simply doesn't show up, same rule
  // `pages/products/[slug].vue` already follows for its own pricing section.
  const PRICING_SLUGS = ['nexstack-pos', 'studio-management']
  const tabs = computed(() => productsStore.products.filter((p) => PRICING_SLUGS.includes(p.slug)))

  const activeSlug = ref('nexstack-pos')
  watch(
    tabs,
    (list) => {
      if (list.length && !list.some((p) => p.slug === activeSlug.value)) activeSlug.value = list[0]!.slug
    },
    { immediate: true }
  )
  const activeProductName = computed(() => tabs.value.find((p) => p.slug === activeSlug.value)?.name ?? '')

  // Cycles differ per product (POS's are CMS-driven, Studio's are fixed
  // monthly/quarterly/yearly) — reset to monthly whenever the selected
  // product changes so a stale cycle index never carries over.
  const selectedMonths = ref(1)
  watch(activeSlug, () => {
    selectedMonths.value = 1
  })

  await useAsyncData('pricing-page', async () => {
    await Promise.all([productsStore.fetchProducts(), posStore.fetchPlans(), studioStore.fetchPlans()])
    return true
  })

  // ── Nexstack POS ─────────────────────────────────────────────────────
  // Cycle-toggle + plan cards themselves live in PosPricingCards.vue (the
  // same component used on the product detail page) — only the feature
  // comparison table's own data is computed here.
  const posVisiblePlans = computed(() =>
    (posStore.plans ?? []).filter((p) => p.is_active).map((p) => ({ ...p, popular: p.code === 'pro' }))
  )
  // Every distinct feature across this product's plans, dedup'd by key,
  // with a ✓/— per plan — real data, nothing invented.
  const posFeatureRows = computed(() => {
    const rows = new Map<string, { label: string; values: Record<string, boolean> }>()
    for (const plan of posVisiblePlans.value) {
      for (const f of plan.features ?? []) {
        const key = f.key ?? f.id ?? f.en
        if (!key) continue
        if (!rows.has(key)) {
          const label = (locale.value === 'en' || locale.value === 'km' ? f[locale.value] : undefined) ?? f.en ?? key
          rows.set(key, { label, values: {} })
        }
        rows.get(key)!.values[plan.code] = true
      }
    }
    return [...rows.values()]
  })

  // ── Studio Management ────────────────────────────────────────────────
  const STUDIO_CYCLES = [
    { months: 1, labelKey: 'studio_pricing.monthly' },
    { months: 3, labelKey: 'studio_pricing.quarterly' },
    { months: 12, labelKey: 'studio_pricing.yearly' }
  ]
  const studioHasPaidPlans = computed(() => studioStore.plans.some((p) => Number(p.price_monthly) > 0))
  const studioIsFree = (plan: StudioPlan) => Number(plan.price_monthly) === 0
  function studioTotalForCycle(plan: StudioPlan, months: number) {
    if (months === 1) return Number(plan.price_monthly)
    if (months === 3) return Number(plan.price_quarterly)
    return Number(plan.price_yearly)
  }
  function studioMonthlyPrice(plan: StudioPlan) {
    return (studioTotalForCycle(plan, selectedMonths.value) / selectedMonths.value).toFixed(2)
  }
  function studioSavingsPct(months: number) {
    const referencePlan = studioStore.plans.find((p) => Number(p.price_monthly) > 0)
    if (!referencePlan) return 0
    const monthly = Number(referencePlan.price_monthly)
    if (!monthly) return 0
    const perMonthAtCycle = studioTotalForCycle(referencePlan, months) / months
    return Math.round((1 - perMonthAtCycle / monthly) * 100)
  }
  function studioAllFeatures(plan: StudioPlan): string[] {
    const items: string[] = []
    items.push(
      plan.max_users ? t('studio_pricing.feature_users', { n: plan.max_users }) : t('studio_pricing.feature_users_unlimited')
    )
    items.push(
      plan.storage_limit_gb
        ? t('studio_pricing.feature_storage', { n: plan.storage_limit_gb })
        : t('studio_pricing.feature_storage_unlimited')
    )
    items.push(
      plan.monthly_order_limit
        ? t('studio_pricing.feature_orders', { n: plan.monthly_order_limit })
        : t('studio_pricing.feature_orders_unlimited')
    )
    if (plan.has_online_gallery) items.push(t('studio_pricing.feature_online_gallery'))
    if (plan.has_reports) items.push(t('studio_pricing.feature_reports'))
    if (plan.has_telegram) items.push(t('studio_pricing.feature_telegram'))
    if (plan.has_api_access) items.push(t('studio_pricing.feature_api'))
    return items
  }
  const studioTopFeatures = (plan: StudioPlan) => studioAllFeatures(plan).slice(0, 7)

  const studioFeatureRows = computed(() => {
    const rows: { label: string; values: Record<string, string | boolean> }[] = [
      { label: t('pricing_hub.row_users'), values: {} },
      { label: t('pricing_hub.row_storage'), values: {} },
      { label: t('pricing_hub.row_orders'), values: {} },
      { label: t('studio_pricing.feature_online_gallery'), values: {} },
      { label: t('studio_pricing.feature_reports'), values: {} },
      { label: t('studio_pricing.feature_telegram'), values: {} },
      { label: t('studio_pricing.feature_api'), values: {} }
    ]
    for (const plan of studioStore.plans) {
      rows[0]!.values[plan.code] = plan.max_users ? String(plan.max_users) : t('studio_pricing.feature_users_unlimited')
      rows[1]!.values[plan.code] = plan.storage_limit_gb
        ? `${plan.storage_limit_gb} GB`
        : t('studio_pricing.feature_storage_unlimited')
      rows[2]!.values[plan.code] = plan.monthly_order_limit
        ? String(plan.monthly_order_limit)
        : t('studio_pricing.feature_orders_unlimited')
      rows[3]!.values[plan.code] = !!plan.has_online_gallery
      rows[4]!.values[plan.code] = !!plan.has_reports
      rows[5]!.values[plan.code] = !!plan.has_telegram
      rows[6]!.values[plan.code] = !!plan.has_api_access
    }
    return rows
  })

  // Same hand-off as pages/products/[slug].vue's goToStudioRegister — this
  // site's onboarding wizard, which calls Studio's real registration API.
  const localePath = useLocalePath()
  function goToStudioRegister(planCode?: string) {
    navigateTo(localePath({ path: '/onboarding/studio-management', query: planCode ? { plan: planCode } : {} }))
  }
</script>

<style scoped>
  .pricing-page {
    padding-top: 120px;
  }
  .hub-header {
    max-width: 620px;
    margin: 0 auto 36px;
  }
  .hub-sub {
    max-width: 480px;
    margin: 0 auto;
  }

  /* ── Product selector ── */
  .product-selector-wrap {
    display: flex;
    justify-content: center;
    margin-bottom: 40px;
    overflow-x: auto;
    -webkit-mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
    mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
  }
  .product-selector {
    flex-wrap: nowrap;
  }
  .product-selector :deep([data-slot='tabs-trigger']) {
    white-space: nowrap;
  }

  /* ── Billing cycle toggle ── */
  .cycle-wrap {
    display: flex;
    justify-content: center;
    margin-bottom: 32px;
  }
  .cycle-track {
    display: inline-flex;
    align-items: center;
    background: color-mix(in srgb, var(--foreground) 6%, transparent);
    border-radius: 999px;
    padding: 4px;
    gap: 2px;
    overflow-x: auto;
    max-width: 100%;
  }
  .cycle-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 16px;
    border: none;
    background: transparent;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 600;
    color: color-mix(in srgb, var(--foreground) 50%, transparent);
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.2s, color 0.2s;
  }
  .cycle-btn--active {
    background: var(--primary);
    color: #fff;
  }
  .cycle-btn__badge {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.22);
  }
  .cycle-btn:not(.cycle-btn--active) .cycle-btn__badge {
    background: color-mix(in srgb, var(--success) 15%, transparent);
    color: var(--success);
  }

  /* ── Plan cards — compact, comparison-first ── */
  .cards-grid {
    display: grid;
    gap: 18px;
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  }
  .cards-grid[data-count='1'] {
    max-width: 320px;
  }
  .cards-grid[data-count='2'] {
    max-width: 680px;
    grid-template-columns: repeat(2, 1fr);
  }
  .cards-grid[data-count='3'] {
    grid-template-columns: repeat(3, 1fr);
  }
  @media (max-width: 720px) {
    .cards-grid,
    .cards-grid[data-count='2'],
    .cards-grid[data-count='3'] {
      grid-template-columns: 1fr !important;
      max-width: 420px;
    }
  }

  .plan-card {
    position: relative;
    border-radius: 18px;
    padding: 24px 22px;
    background: var(--card);
    border: 1px solid color-mix(in srgb, var(--foreground) 8%, transparent);
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  .plan-card--featured {
    border: 2px solid var(--primary);
    box-shadow: 0 12px 32px color-mix(in srgb, var(--primary) 12%, transparent);
  }
  .popular-badge {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
  }

  .plan-name {
    font-size: 1.05rem;
    font-weight: 800;
    margin: 0;
  }
  .plan-desc {
    font-size: 0.83rem;
    color: color-mix(in srgb, var(--foreground) 55%, transparent);
    margin: 0;
    line-height: 1.5;
  }

  .price-block {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-height: 56px;
  }
  .price-row {
    display: flex;
    align-items: flex-start;
    gap: 2px;
    line-height: 1;
  }
  .price-currency {
    font-size: 0.95rem;
    font-weight: 700;
    color: color-mix(in srgb, var(--foreground) 50%, transparent);
    margin-top: 4px;
  }
  .price-amount {
    font-size: 2.4rem;
    font-weight: 900;
    letter-spacing: -1.5px;
  }
  .price-amount--free {
    color: var(--success);
  }
  .price-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
  }
  .price-per {
    font-size: 0.75rem;
    color: color-mix(in srgb, var(--foreground) 50%, transparent);
  }
  .price-unavailable {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    font-style: italic;
    color: color-mix(in srgb, var(--foreground) 45%, transparent);
  }

  .plan-divider {
    height: 1px;
    background: color-mix(in srgb, var(--foreground) 10%, transparent);
  }

  .feature-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex-grow: 1;
  }
  .feature-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.82rem;
  }
  .feature-check {
    color: var(--primary);
    flex-shrink: 0;
  }

  .plan-cta {
    margin-top: auto;
  }
</style>
