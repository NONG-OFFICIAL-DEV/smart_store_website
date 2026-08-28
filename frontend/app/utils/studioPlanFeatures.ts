import type { StudioPlan } from '~/types'

function resolveText(text: { en: string; km?: string } | undefined, locale: string): string {
  if (!text) return ''
  return (locale === 'km' ? text.km : text.en) || text.en || ''
}

// Studio's own admin manages a per-plan list of features (a plan's
// `feature_labels` — see the StudioPlan type) — this is the only source
// for plan feature copy now, no static i18n fallback. `key` identifies the
// underlying feature, shared across plans that both list it (used to line
// up comparison-table rows); `label` is that feature's display name,
// `value` is this specific plan's text for it — both resolved to `locale`.
export function studioPlanFeatureList(
  plan: StudioPlan,
  locale: string
): Array<{ key: string; label: string; value: string }> {
  return (plan.feature_labels ?? [])
    .map((f) => ({
      key: f.key,
      label: resolveText(f.label, locale),
      value: resolveText(f.value, locale)
    }))
    .filter((f) => !!f.value)
}
