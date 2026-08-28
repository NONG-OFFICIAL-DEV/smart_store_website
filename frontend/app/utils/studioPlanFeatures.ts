import type { StudioPlan } from '~/types'

function resolveText(text: { en: string; km?: string } | undefined, locale: string): string {
  if (!text) return ''
  return (locale === 'km' ? text.km : text.en) || text.en || ''
}

// Studio resolves `feature_list` server-side against its own live
// PlanFeatureListing catalog (see PlanFeatureListingService::resolveForPlan
// in that repo) — `key` is a real catalog id shared across every plan,
// `value_type` says explicitly whether `value` is a boolean or {en,km}
// text, and duplicate/mismatched rows can't happen since the catalog is
// the only place labels are ever typed. This file only formats what
// Studio already resolved — no guessing, no fuzzy matching.

// Per-plan card bullet list: a true boolean feature shows just its label
// (the bullet's own checkmark icon already says "included"), a false
// boolean feature is omitted entirely (a bullet list shows what you get,
// not what you don't), and a text feature shows "Label: Value" if it has
// a value.
export function studioPlanBullets(plan: StudioPlan, locale: string): string[] {
  return (plan.feature_list ?? [])
    .map((f) => {
      const label = resolveText(f.label, locale)
      if (f.value_type === 'boolean') {
        return f.value === true ? label : null
      }
      const value = resolveText(f.value as { en: string; km?: string }, locale)
      return value ? `${label}: ${value}` : null
    })
    .filter((line): line is string => !!line)
}

// Comparison-table rows: `value` is already typed (boolean | string) via
// value_type, so PricingComparisonTable.vue can render a checkmark/dash
// or text directly with no further inference. `key` is genuinely shared
// across plans now, so callers can align rows across plans by it.
export function studioPlanComparisonFeatures(
  plan: StudioPlan,
  locale: string
): Array<{ key: string; label: string; value: boolean | string }> {
  return (plan.feature_list ?? []).map((f) => ({
    key: f.key,
    label: resolveText(f.label, locale),
    value: f.value_type === 'boolean' ? (f.value as boolean) : resolveText(f.value as { en: string; km?: string }, locale)
  }))
}
