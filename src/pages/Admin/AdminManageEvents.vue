<template>
  <div class="min-h-screen bg-gray-50">
    <AppNavbar />
    
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <Breadcrumb :items="[
        {name: 'Home', to: '/'}, 
        {name: 'Dashboard', to: '/admin/dashboard'}, 
        {name: 'Manage Events'}
      ]" />

      <!-- Header -->
      <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Event Management</h1>
            <p class="mt-2 text-gray-600">Review and manage all events in the system</p>
          </div>
          <div class="mt-4 sm:mt-0 flex space-x-3">
            <router-link to="/admin/dashboard" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
              </svg>
              Dashboard
            </router-link>
            <router-link to="/admin/reports" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 inline-flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              Reports
            </router-link>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
          <div class="flex flex-wrap gap-2">
            <button 
              v-for="status in statusFilters" 
              :key="status.key"
              @click="selectedStatus = selectedStatus === status.key ? '' : status.key"
              :class="selectedStatus === status.key ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
            >
              {{ status.name }}
              <span v-if="status.count !== undefined" class="ml-2 bg-white/20 px-2 py-0.5 rounded-full text-xs">
                {{ status.count }}
              </span>
            </button>
          </div>
          <div class="flex items-center space-x-4">
            <input 
              v-model="searchQuery"
              placeholder="Search events..." 
              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            />
          </div>
        </div>
      </div>

      <!-- Events List -->
      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
        <p class="text-gray-600 mt-4">Loading events...</p>
      </div>

      <div v-else-if="filteredEvents.length === 0" class="text-center py-12">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No events found</h3>
        <p class="text-gray-600">Try adjusting your filters or search criteria.</p>
      </div>

      <!-- Events Grid -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="event in paginatedEvents" :key="event.id" class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
          <!-- Event Image/Poster -->
          <div class="h-48 bg-gradient-to-br from-indigo-400 to-purple-600 relative overflow-hidden">
            <img v-if="event.poster" :src="event.poster" :alt="event.title" class="w-full h-full object-cover" />
            <div v-else class="flex items-center justify-center h-full text-white">
              <svg class="w-16 h-16 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <!-- Status Badge -->
            <div class="absolute top-3 right-3">
              <span :class="getStatusBadgeClass(event.status)" class="px-3 py-1 rounded-full text-xs font-medium">
                {{ event.status }}
              </span>
            </div>
          </div>

          <!-- Event Content -->
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">{{ event.title }}</h3>
            
            <!-- Event Details -->
            <div class="space-y-2 mb-4">
              <div class="flex items-center text-sm text-gray-600">
                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ formatDate(event.date) }}
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                </svg>
                {{ event.location || 'Location TBD' }}
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                {{ event.organizer }}
              </div>
            </div>

            <!-- Category and Registrations -->
            <div class="flex items-center justify-between mb-4">
              <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-xs font-medium">
                {{ event.category }}
              </span>
              <span class="text-xs text-gray-500">
                {{ event.registrations }} registered
              </span>
            </div>

            <!-- Action Buttons -->
            <div v-if="event.status === 'pending'" class="flex space-x-2">
              <button @click="approveEvent(event)" class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                Approve
              </button>
              <button @click="rejectEvent(event)" class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                Reject
              </button>
            </div>
            <div v-else class="space-y-2">
              <button @click="viewEvent(event)" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                View Details
              </button>
              <button @click="deleteEvent(event)" class="w-full bg-red-100 text-red-700 py-2 px-4 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                Delete Event
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="mt-8 flex justify-center">
        <Pagination
          :current-page="currentPage"
          :total-pages="totalPages"
          @page-change="currentPage = $event"
        />
      </div>
    </main>
  </div>
</template>

<script>
import AppNavbar from '@/components/AppNavbar.vue'
import Breadcrumb from '@/components/Breadcrumb.vue'
import Pagination from '@/components/Pagination.vue'

export default {
  name: 'AdminManageEvents',
  components: { AppNavbar, Breadcrumb, Pagination },
  data() {
    return { 
      events: [],
      loading: true,
      selectedStatus: '',
      searchQuery: '',
      currentPage: 1,
      itemsPerPage: 10
    }
  },
  async created() {
    await this.loadEvents()
  },
  computed: {
    statusFilters() {
      const all = this.events.length
      const pending = this.events.filter(e => e.status === 'pending').length
      const approved = this.events.filter(e => e.status === 'approved').length
      const rejected = this.events.filter(e => e.status === 'rejected').length
      
      return [
        { key: '', name: 'All Events', count: all },
        { key: 'pending', name: 'Pending', count: pending },
        { key: 'approved', name: 'Approved', count: approved },
        { key: 'rejected', name: 'Rejected', count: rejected }
      ]
    },
    filteredEvents() {
      let filtered = this.events

      if (this.selectedStatus) {
        filtered = filtered.filter(e => e.status === this.selectedStatus)
      }

      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(e => 
          e.title.toLowerCase().includes(query) ||
          e.organizer.toLowerCase().includes(query) ||
          e.category.toLowerCase().includes(query)
        )
      }

      return filtered
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
    async approveEvent(event) {
      try {
        await this.$http.put(`/api/events/${event.id}/approve`)
        event.status = 'approved'
        alert(`Event "${event.title}" approved successfully!`)
      } catch (err) {
        console.error(err)
        alert('Failed to approve event')
      }
    },
    async rejectEvent(event) {
      try {
        await this.$http.put(`/api/events/${event.id}/reject`)
        event.status = 'rejected'
        alert(`Event "${event.title}" rejected`)
      } catch (err) {
        console.error(err)
        alert('Failed to reject event')
      }
    },
    viewEvent(event) {
      this.$router.push(`/admin/event/${event.id}`)
    },
    async deleteEvent(event) {
      if (confirm(`Are you sure you want to delete "${event.title}"?`)) {
        try {
          await this.$http.delete(`/api/events/${event.id}`)
          this.events = this.events.filter(e => e.id !== event.id)
          alert('Event deleted successfully')
        } catch (err) {
          console.error(err)
          alert('Failed to delete event')
        }
      }
    },
    getStatusBadgeClass(status) {
      switch(status) {
        case 'approved': return 'bg-green-500 text-white'
        case 'pending': return 'bg-amber-500 text-white'
        case 'rejected': return 'bg-red-500 text-white'
        default: return 'bg-gray-500 text-white'
      }
    },
    async loadEvents() {
      try {
        this.loading = true
        const response = await this.$http.get('/api/events')
        this.events = response.data.map(event => ({
          id: event.id,
          title: event.title,
          organizer: event.organizer?.name || 'Unknown',
          date: event.start_at || event.created_at,
          status: event.status || 'pending',
          category: event.category || 'General',
          location: event.location || 'TBD',
          registrations: event.registrations?.length || 0,
          poster: event.poster_path ? `http://localhost:8000/storage/${event.poster_path}` : null
        }))
      } catch (err) {
        console.error('Failed to load events:', err)
        // Fallback to mock data if API fails
        this.events = [
          { id: 1, title: 'Tech Workshop 2024', organizer: 'John Doe', date: '2024-02-15', status: 'pending', category: 'Workshop', location: 'Room A', registrations: 25 },
          { id: 2, title: 'Cultural Festival', organizer: 'Jane Smith', date: '2024-02-20', status: 'approved', category: 'Cultural', location: 'Main Hall', registrations: 150 },
          { id: 3, title: 'Sports Tournament', organizer: 'Mike Johnson', date: '2024-02-25', status: 'rejected', category: 'Sports', location: 'Stadium', registrations: 0 }
        ]
      } finally {
        this.loading = false
      }
    },
    formatDate(date) {
      if (!date) return 'TBD'
      return new Date(date).toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        year: 'numeric'
      })
    }
  }
}
</script>