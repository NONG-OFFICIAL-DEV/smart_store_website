import type { StudioPlan } from '~/types'

function resolveText(text: { en: string; km?: string } | undefined, locale: string): string {
  if (!text) return ''
  return (locale === 'km' ? text.km : text.en) || text.en || ''
}

// Studio's own admin manages a per-plan list of features (a plan's
// `feature_labels` — see the StudioPlan type) — this is the only source
// for plan feature copy now, no static i18n fallback. `key` is only a
// stable list-render id generated per-row in Studio's admin form
// (crypto.randomUUID()) — it is NOT shared across plans, so it can never
// be used to line up comparison-table rows between two plans (two plans'
// "Users" rows have unrelated, independently-generated keys). Callers that
// need to align rows across plans (see pricing.vue's studioFeatureRows)
// must match on trimmed `label` text instead. `value` is this specific
// plan's text for that row — both `label`/`value` resolved to `locale`.
export function studioPlanFeatureList(
  plan: StudioPlan,
  locale: string
): Array<{ key: string; label: string; value: string }> {
  const resolved = (plan.feature_labels ?? [])
    .map((f) => ({
      key: f.key,
      label: resolveText(f.label, locale).trim(),
      value: resolveText(f.value, locale)
    }))
    .filter((f) => !!f.value && !!f.label)

  // Guard against an admin accidentally attaching the same feature label
  // to a plan twice — keep the last entry rather than showing the
  // duplicate as two separate bullets/rows.
  const labels = resolved.map((f) => f.label)
  return resolved.filter((f, i) => labels.lastIndexOf(f.label) === i)
}
