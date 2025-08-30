<template>
  <div v-if="totalPages > 1" class="flex justify-center items-center space-x-2 mt-6">
    <button 
      @click="$emit('page-change', currentPage - 1)"
      :disabled="currentPage === 1"
      class="px-3 py-1 border rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
    >
      Previous
    </button>
    
    <button
      v-for="page in visiblePages"
      :key="page"
      @click="$emit('page-change', page)"
      :class="[
        'px-3 py-1 border rounded',
        page === currentPage ? 'bg-indigo-600 text-white' : 'hover:bg-gray-50'
      ]"
    >
      {{ page }}
    </button>
    
    <button 
      @click="$emit('page-change', currentPage + 1)"
      :disabled="currentPage === totalPages"
      class="px-3 py-1 border rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
    >
      Next
    </button>
  </div>
</template>

<script>
export default {
  name: 'Pagination',
  props: {
    currentPage: { type: Number, required: true },
    totalPages: { type: Number, required: true }
  },
  computed: {
    visiblePages() {
      const pages = []
      const start = Math.max(1, this.currentPage - 2)
      const end = Math.min(this.totalPages, this.currentPage + 2)
      
      for (let i = start; i <= end; i++) {
        pages.push(i)
      }
      return pages
    }
  }
}
</script>