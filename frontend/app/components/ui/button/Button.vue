<script setup lang="ts">
import type { PrimitiveProps } from 'reka-ui'
import type { HTMLAttributes } from 'vue'
import type { ButtonVariants } from '.'
import { computed, useAttrs } from 'vue'
import { NuxtLink, NuxtLinkLocale } from '#components'
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
//
// Every /admin/** page opts out of i18n routing (`defineI18nRoute(false)`
// — the CMS panel is never localized, see nuxt.config.ts's routeRules
// forcing it ssr:false too). @nuxtjs/i18n's localeRoute() has a bug where
// resolving a path with no locale-suffixed route name registered (exactly
// the admin case) throws inside Vue Router and NuxtLinkLocale silently
// swallows it, rendering a real <a> with no href — a dead button, no
// console error. Admin links need no locale resolution anyway, so they
// skip NuxtLinkLocale entirely and use plain NuxtLink instead.
// `to` isn't a declared prop (Primitive forwards it through $attrs to
// whatever `as` resolves to) — read it via useAttrs() rather than adding
// it to Props, which would pull it out of the fallthrough chain and break
// forwarding for every other as="NuxtLink" call site.
const attrs = useAttrs()
const isAdminLink = computed(() => typeof attrs.to === 'string' && (attrs.to as string).startsWith('/admin'))
const resolvedAs = computed(() => {
  if (props.as !== 'NuxtLink') return props.as
  return isAdminLink.value ? NuxtLink : NuxtLinkLocale
})
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
