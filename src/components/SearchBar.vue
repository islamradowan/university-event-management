<template>
  <div class="relative">
    <input v-model="q" @input="emit" placeholder="Search events..." class="border rounded px-3 py-2 text-sm" />
  </div>
</template>

<script>
export default {
  name: 'SearchBar',
  data() { return { q: '' } },
  methods: {
    emit() { this.$emit('search', this.q) }
  }
}
</script>
