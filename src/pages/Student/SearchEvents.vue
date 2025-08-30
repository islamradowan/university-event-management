<template>
  <div>
    <Navbar />
    <main class="max-w-6xl mx-auto p-6">
      <Breadcrumb :items="[
        {name: 'Home', to: '/'}, 
        {name: 'Dashboard', to: '/student/dashboard'}, 
        {name: 'Search Events', to: ''}
      ]" />
      
      <h1 class="text-2xl font-bold mb-6">Search Events</h1>
      
      <AdvancedSearch @filters-changed="onFiltersChanged" />
      
      <div v-if="loading" class="text-center py-8">Loading...</div>
      
      <div v-else-if="!filteredEvents.length" class="text-center py-8 text-gray-500">
        No events found matching your criteria
      </div>
      
      <div v-else>
        <div class="mb-4 text-sm text-gray-600">
          Found {{ filteredEvents.length }} event(s)
        </div>
        
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <EventCard v-for="e in paginatedEvents" :key="e.id" :event="e" />
        </div>
        
        <Pagination 
          :current-page="currentPage" 
          :total-pages="totalPages" 
          @page-change="currentPage = $event" 
        />
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import EventCard from '@/components/EventCard.vue'
import Breadcrumb from '@/components/Breadcrumb.vue'
import Pagination from '@/components/Pagination.vue'
import AdvancedSearch from '@/components/AdvancedSearch.vue'
import { apiEventToView } from '@/utils/apiMappers'

export default {
  name: 'SearchEvents',
  components: { Navbar, Footer, EventCard, Breadcrumb, Pagination, AdvancedSearch },
  data() {
    return {
      events: [],
      filters: {},
      loading: true,
      currentPage: 1,
      itemsPerPage: 12
    }
  },
  async created() {
    await this.loadEvents()
  },
  computed: {
    filteredEvents() {
      let result = [...this.events]
      
      if (this.filters.search) {
        const q = this.filters.search.toLowerCase()
        result = result.filter(e => 
          (e.title + e.description + e.location).toLowerCase().includes(q)
        )
      }
      
      if (this.filters.category) {
        result = result.filter(e => e.category === this.filters.category)
      }
      
      if (this.filters.status) {
        result = result.filter(e => e.status === this.filters.status)
      }
      
      if (this.filters.location) {
        const loc = this.filters.location.toLowerCase()
        result = result.filter(e => e.location?.toLowerCase().includes(loc))
      }
      
      if (this.filters.startDate) {
        result = result.filter(e => new Date(e.start_at) >= new Date(this.filters.startDate))
      }
      if (this.filters.endDate) {
        result = result.filter(e => new Date(e.start_at) <= new Date(this.filters.endDate))
      }
      
      if (this.filters.minCapacity) {
        result = result.filter(e => e.capacity >= this.filters.minCapacity)
      }
      if (this.filters.maxCapacity) {
        result = result.filter(e => e.capacity <= this.filters.maxCapacity)
      }
      
      // Sort
      if (this.filters.sortBy) {
        result.sort((a, b) => {
          let aVal = a[this.filters.sortBy]
          let bVal = b[this.filters.sortBy]
          
          if (this.filters.sortBy.includes('_at')) {
            aVal = new Date(aVal)
            bVal = new Date(bVal)
          }
          
          if (this.filters.sortOrder === 'desc') {
            return bVal > aVal ? 1 : -1
          }
          return aVal > bVal ? 1 : -1
        })
      }
      
      return result
    },
    paginatedEvents() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      return this.filteredEvents.slice(start, start + this.itemsPerPage)
    },
    totalPages() {
      return Math.ceil(this.filteredEvents.length / this.itemsPerPage)
    }
  },
  methods: {
    async loadEvents() {
      try {
        this.loading = true
        const apiEvents = await this.$store.dispatch('fetchEvents')
        this.events = apiEvents.map(apiEventToView)
      } catch (error) {
        console.error('Failed to load events:', error)
      } finally {
        this.loading = false
      }
    },
    onFiltersChanged(filters) {
      this.filters = filters
      this.currentPage = 1
    }
  }
}
</script>