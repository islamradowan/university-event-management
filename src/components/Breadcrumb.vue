<template>
  <nav class="flex mb-4 text-sm text-gray-600">
    <span v-for="(item, index) in items" :key="index" class="flex items-center">
      <router-link 
        v-if="item.to"
        :to="item.to"
        class="text-gray-600 hover:text-indigo-600"
      >
        {{ item.name }}
      </router-link>
      <span 
        v-else
        class="text-gray-900 font-medium"
      >
        {{ item.name }}
      </span>
      <span v-if="index < items.length - 1" class="mx-2">/</span>
    </span>
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