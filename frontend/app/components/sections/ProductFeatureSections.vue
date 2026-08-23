<template>
  <template v-for="section in activeSections" :key="section.id">
    <FeatureGridSection v-if="section.type === 'feature-grid'" :section="section" />
    <FeatureShowcaseSection v-else-if="section.type === 'feature-showcase'" :section="section" />
    <FeatureDetailSection v-else-if="section.type === 'feature-detail'" :section="section" />
    <WorkflowSection v-else-if="section.type === 'workflow'" :section="section" />
  </template>
</template>

<script setup lang="ts">
  import type { ProductFeatureSection } from '~/types'

  const props = defineProps<{ sections: ProductFeatureSection[] }>()

  // Sections/items are already filtered to active-only and locale-resolved
  // server-side (see Api\Public\ProductController::show) — sorted here
  // defensively so display order always matches sort_order regardless of
  // whatever order the API happened to return them in.
  const activeSections = computed(() =>
    [...props.sections].filter((s) => s.is_active).sort((a, b) => a.sort_order - b.sort_order)
  )
</script>
