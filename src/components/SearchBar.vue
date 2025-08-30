<template>
  <div class="relative">
    <input v-model="q" placeholder="Search events..." class="border rounded px-3 py-2 text-sm" />
  </div>
</template>

<script>
import { debounce } from '@/utils/debounce'

export default {
  name: 'SearchBar',
  data() { return { q: '' } },
  created() {
    this.debouncedEmit = debounce(this.emit, 300)
  },
  methods: {
    emit() { this.$emit('search', this.q) }
  },
  watch: {
    q() { this.debouncedEmit() }
  }
}
</script>
