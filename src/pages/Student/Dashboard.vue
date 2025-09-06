<template>
  <div class="min-h-screen bg-gray-50">
    <AppNavbar @search="onSearch" />
    
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Welcome back!</h1>
            <p class="mt-2 text-gray-600">Discover amazing events happening around campus</p>
          </div>
          <div class="mt-4 sm:mt-0 flex space-x-3">
            <router-link to="/student/my-events" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              My Events
            </router-link>
            <router-link to="/student/calendar" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              Calendar View
            </router-link>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Available Events</p>
              <p class="text-2xl font-bold text-gray-900">{{ events.length }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">My Registrations</p>
              <p class="text-2xl font-bold text-gray-900">{{ myRegistrations.length }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Events Attended</p>
              <p class="text-2xl font-bold text-gray-900">0</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
          <div class="flex flex-wrap gap-2">
            <button 
              v-for="category in categories" 
              :key="category"
              @click="selectedCategory = selectedCategory === category ? '' : category"
              :class="selectedCategory === category ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
            >
              {{ category }}
            </button>
          </div>
          <div class="flex items-center space-x-4">
            <select v-model="sortBy" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
              <option value="date">Sort by Date</option>
              <option value="title">Sort by Title</option>
              <option value="popularity">Sort by Popularity</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Events Grid -->
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
        <p class="text-gray-600">Try adjusting your filters or check back later for new events.</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="event in paginatedEvents" :key="event.id" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow group">
          <!-- Event Image -->
          <div class="relative h-48 overflow-hidden">
            <OptimizedImage 
              v-if="event.poster" 
              :src="event.poster" 
              :alt="event.title"
              :width="600"
              :quality="80"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
            <div 
              v-else 
              class="w-full h-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center"
            >
              <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            
            <!-- Status Badge -->
            <div class="absolute top-4 right-4">
              <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                Available
              </span>
            </div>
            
            <!-- Category Badge -->
            <div class="absolute top-4 left-4">
              <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-medium">
                {{ event.category }}
              </span>
            </div>
          </div>

          <!-- Event Content -->
          <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">
              {{ event.title }}
            </h3>
            
            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
              {{ event.description || 'No description available' }}
            </p>
            
            <!-- Event Details -->
            <div class="space-y-2 mb-4">
              <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ formatDate(event.date || event.start_at) }} • {{ formatTime(event.time || event.start_at) }}
              </div>
              
              <div v-if="event.location" class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ event.location }}
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-2">
              <router-link 
                :to="`/student/event/${event.id}`" 
                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center"
              >
                View Details
              </router-link>
              
              <button 
                v-if="!isRegistered(event.id)"
                @click="quickRegister(event.id)" 
                :disabled="registeringEvents[event.id]"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center disabled:opacity-50"
              >
                <svg v-if="registeringEvents[event.id]" class="animate-spin w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ registeringEvents[event.id] ? 'Registering...' : 'Register' }}
              </button>
              
              <button 
                v-else
                @click="quickUnregister(event.id)" 
                :disabled="unregisteringEvents[event.id]"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center disabled:opacity-50"
              >
                <svg v-if="unregisteringEvents[event.id]" class="animate-spin w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ unregisteringEvents[event.id] ? 'Unregistering...' : 'Unregister' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="mt-8">
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
import Pagination from '@/components/Pagination.vue'
import OptimizedImage from '@/components/OptimizedImage.vue'
import { apiEventToView } from '@/utils/apiMappers'

export default {
  name: 'StudentDashboard',
  components: { AppNavbar, Pagination, OptimizedImage },
  data() {
    return { 
      events: [],
      myRegistrations: [],
      loading: true,
      registeringEvents: {},
      unregisteringEvents: {},
      q: '',
      selectedCategory: '',
      sortBy: 'date',
      currentPage: 1,
      itemsPerPage: 9,
      categories: ['All', 'Workshop', 'Seminar', 'Cultural', 'Sports', 'Competition']
    }
  },
  async created() {
    await this.loadEvents()
    await this.loadMyRegistrations()
  },
  computed: {
    filteredEvents() {
      let filtered = this.events

      // Search filter
      if (this.q) {
        const query = this.q.toLowerCase()
        filtered = filtered.filter(e => 
          (e.title + e.description + e.location).toLowerCase().includes(query)
        )
      }

      // Category filter
      if (this.selectedCategory && this.selectedCategory !== 'All') {
        filtered = filtered.filter(e => e.category === this.selectedCategory)
      }

      // Sort
      if (this.sortBy === 'date') {
        filtered.sort((a, b) => new Date(a.start_at) - new Date(b.start_at))
      } else if (this.sortBy === 'title') {
        filtered.sort((a, b) => a.title.localeCompare(b.title))
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
    async loadEvents() {
      try {
        const apiEvents = await this.$store.dispatch('fetchEvents')
        this.events = apiEvents.map(apiEventToView)
      } catch (error) {
        console.error('Failed to load events:', error)
      } finally {
        this.loading = false
      }
    },
    async loadMyRegistrations() {
      try {
        this.myRegistrations = await this.$store.dispatch('fetchMyRegistrations')
      } catch (error) {
        console.error('Failed to load registrations:', error)
      }
    },
    onSearch(q) { 
      this.q = q
      this.currentPage = 1
    },
    async quickRegister(eventId) {
      try {
        this.$set(this.registeringEvents, eventId, true)
        await this.$store.dispatch('registerForEvent', eventId)
        this.myRegistrations.push({ event_id: eventId })
        alert('Successfully registered for event!')
      } catch (err) {
        console.error(err)
        alert('Registration failed. Please try again.')
      } finally {
        this.$set(this.registeringEvents, eventId, false)
      }
    },
    async quickUnregister(eventId) {
      try {
        this.$set(this.unregisteringEvents, eventId, true)
        await this.$store.dispatch('unregisterFromEvent', eventId)
        this.myRegistrations = this.myRegistrations.filter(reg => reg.event_id !== eventId)
        alert('Successfully unregistered from event!')
      } catch (err) {
        console.error(err)
        alert('Unregistration failed. Please try again.')
      } finally {
        this.$set(this.unregisteringEvents, eventId, false)
      }
    },
    isRegistered(eventId) {
      return this.myRegistrations.some(reg => reg.event_id === eventId)
    },
    formatDate(date) {
      if (!date) return 'TBD'
      return new Date(date).toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        year: 'numeric'
      })
    },
    formatTime(time) {
      if (!time) return 'TBD'
      if (typeof time === 'string' && time.includes('T')) {
        return new Date(time).toLocaleTimeString('en-US', { 
          hour: 'numeric', 
          minute: '2-digit',
          hour12: true
        })
      }
      return time
    }
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>