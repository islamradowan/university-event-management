<template>
  <nav class="flex mb-4 text-sm text-gray-600">
    <router-link 
      v-for="(item, index) in items" 
      :key="index"
      :to="item.to"
      :class="[
        'hover:text-indigo-600',
        index === items.length - 1 ? 'text-gray-900 font-medium' : 'text-gray-600'
      ]"
    >
      {{ item.name }}
      <span v-if="index < items.length - 1" class="mx-2">/</span>
    </router-link>
  </nav>
</template>

<script>
export default {
  name: 'Breadcrumb',
  props: {
    items: {
      type: Array,
      required: true
    }
  }
}
</script>