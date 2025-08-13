<template>
  <header class="bg-white shadow-sm">
    <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
      <router-link to="/" class="text-xl font-semibold">UniEvents</router-link>

      <div class="flex items-center space-x-3">
        <SearchBar v-if="showSearch" @search="onSearch" />

        <nav class="hidden sm:flex items-center space-x-3 text-sm">
          <router-link to="/" class="px-2 py-1 hover:bg-slate-100 rounded">Home</router-link>
          <router-link to="/student/dashboard" class="px-2 py-1 hover:bg-slate-100 rounded">Student</router-link>
          <router-link to="/organizer/dashboard" class="px-2 py-1 hover:bg-slate-100 rounded">Organizer</router-link>
          <router-link to="/admin/dashboard" class="px-2 py-1 hover:bg-slate-100 rounded">Admin</router-link>
        </nav>

        <div class="flex items-center space-x-2">
          <router-link to="/login" class="text-sm bg-indigo-600 text-white px-3 py-1 rounded">Sign in</router-link>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import SearchBar from './SearchBar.vue'

export default {
  name: 'AppNavbar',
  components: { SearchBar },
  props: {
    showSearch: { type: Boolean, default: true }
  },
  methods: {
    onSearch(q) {
      this.$emit('search', q)
    }
  }
}
</script>
