<script setup lang="ts">
import type { PrimitiveProps } from 'reka-ui'
import type { HTMLAttributes } from 'vue'
import type { ButtonVariants } from '.'
import { computed } from 'vue'
import { NuxtLinkLocale } from '#components'
import { Primitive } from 'reka-ui'
import { cn } from '@/lib/utils'
import { buttonVariants } from '.'

interface Props extends PrimitiveProps {
  variant?: ButtonVariants['variant']
  size?: ButtonVariants['size']
  class?: HTMLAttributes['class']
}

const props = withDefaults(defineProps<Props>(), {
  as: 'button',
})

// Primitive renders `as` via Vue's runtime h(), which — unlike the
// template compiler — never resolves a plain string against a component.
// `as="NuxtLink"` would otherwise render a literal, inert <NuxtLink>
// custom element (no href, no navigation, no error) instead of Nuxt's
// real link component. NuxtLink isn't a runtime-registered global
// component (Nuxt's auto-import splices it into each file at compile
// time), so resolveDynamicComponent() can't find it either — it has to
// be mapped to the actual imported component explicitly. Resolves to
// NuxtLinkLocale (not plain NuxtLink) so every `as="NuxtLink"` call site
// automatically gets locale-aware routing (/km/... prefix) for free —
// @nuxtjs/i18n doesn't patch NuxtLink itself, only this wrapper.
const resolvedAs = computed(() => (props.as === 'NuxtLink' ? NuxtLinkLocale : props.as))
</script>

<template>
  <Primitive
    data-slot="button"
    :data-variant="variant"
    :data-size="size"
    :as="resolvedAs"
    :as-child="asChild"
    :class="cn(buttonVariants({ variant, size }), props.class)"
  >
    <slot />
  </Primitive>
</template>
