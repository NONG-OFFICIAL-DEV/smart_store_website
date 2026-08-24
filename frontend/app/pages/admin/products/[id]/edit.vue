<template>
  <ProductEditorForm />
</template>

<script setup lang="ts">
// See pages/admin/index.vue for why every admin page opts out of i18n routing.
defineI18nRoute(false)
  definePageMeta({ layout: 'admin' })
</script>
