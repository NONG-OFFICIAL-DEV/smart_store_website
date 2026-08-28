import type { StudioPlan } from '~/types'

// Studio's own admin sets a per-plan, per-locale label for each feature
// dimension it wants shown (a plan's `feature_labels`, e.g.
// `{ max_users: { en: 'Up to 2 users', km: '...' } }`) — this is now the
// only source for that copy (no static i18n fallback). Returns null when
// the admin hasn't set a label for that dimension, so callers can skip it.
export function studioFeatureLabel(plan: StudioPlan, key: string, locale: string): string | null {
  const entry = plan.feature_labels?.[key]
  if (!entry) return null
  return (locale === 'km' ? entry.km : entry.en) || entry.en || null
}
