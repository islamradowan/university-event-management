<template>
  <div class="min-h-screen bg-gray-50">
    <AppNavbar />
    
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <Breadcrumb :items="[
        {name: 'Home', to: '/'}, 
        {name: 'Dashboard', to: '/organizer/dashboard'}, 
        {name: 'Event Details'}
      ]" />

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
        <p class="text-gray-600 mt-4">Loading event details...</p>
      </div>

      <div v-else-if="event" class="space-y-8">
        <!-- Event Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="relative h-64 bg-gradient-to-br from-indigo-500 to-purple-600">
            <img v-if="event.poster" :src="event.poster" :alt="event.title" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end">
              <div class="p-8 text-white">
                <div class="flex items-center space-x-3 mb-4">
                  <span :class="getStatusBadgeClass(event.status)" class="px-3 py-1 rounded-full text-xs font-medium">
                    {{ event.status || 'Draft' }}
                  </span>
                  <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium">
                    {{ event.category }}
                  </span>
                </div>
                <h1 class="text-3xl font-bold mb-2">{{ event.title }}</h1>
                <div class="flex items-center space-x-6 text-sm">
                  <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ formatDate(event.date || event.start_at) }}
                  </div>
                  <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ event.location }}
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="absolute top-4 right-4 flex space-x-2">
              <router-link 
                :to="`/organizer/edit/${event.id}`"
                class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Event
              </router-link>
            </div>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Registered</p>
                <p class="text-2xl font-bold text-gray-900">{{ attendees.length }}</p>
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
                <p class="text-sm font-medium text-gray-600">Checked In</p>
                <p class="text-2xl font-bold text-gray-900">{{ checkedInCount }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Capacity</p>
                <p class="text-2xl font-bold text-gray-900">{{ event.capacity || 'Unlimited' }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Avg Rating</p>
                <p class="text-2xl font-bold text-gray-900">4.5</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Event Description -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Description</h3>
          <p class="text-gray-600 leading-relaxed">
            {{ event.description || 'No description available for this event.' }}
          </p>
        </div>

        <!-- Attendees List -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="px-8 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold text-gray-900">Attendees</h3>
              <div class="flex items-center space-x-2">
                <button 
                  @click="exportAttendees"
                  class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                  Export CSV
                </button>
              </div>
            </div>
          </div>

          <div v-if="attendees.length === 0" class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
            </div>
            <h4 class="text-lg font-medium text-gray-900 mb-2">No attendees yet</h4>
            <p class="text-gray-600">Registrations will appear here once students sign up for your event.</p>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                  <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Date</th>
                  <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="attendee in attendees" :key="attendee.id" class="hover:bg-gray-50">
                  <td class="px-8 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                          <span class="text-sm font-medium text-indigo-600">
                            {{ attendee.name.charAt(0).toUpperCase() }}
                          </span>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ attendee.name }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ attendee.email }}
                  </td>
                  <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ formatDate(attendee.registered_at) }}
                  </td>
                  <td class="px-8 py-4 whitespace-nowrap">
                    <span v-if="attendee.checked_in" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                      Checked In
                    </span>
                    <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                      <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                      </svg>
                      Registered
                    </span>
                  </td>
                  <td class="px-8 py-4 whitespace-nowrap text-sm font-medium">
                    <button 
                      v-if="!attendee.checked_in"
                      @click="markCheckIn(attendee)"
                      class="text-indigo-600 hover:text-indigo-900 transition-colors"
                    >
                      Check In
                    </button>
                    <span v-else class="text-gray-400">-</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import AppNavbar from '@/components/AppNavbar.vue'
import Breadcrumb from '@/components/Breadcrumb.vue'
import { apiEventToView } from '@/utils/apiMappers'

export default {
  name: 'OrganizerEventDetails',
  components: { AppNavbar, Breadcrumb },
  data() {
    return { 
      event: null, 
      attendees: [],
      loading: true
    }
  },
  async created() {
    await this.loadEventDetails()
  },
  computed: {
    checkedInCount() {
      return this.attendees.filter(a => a.checked_in).length
    }
  },
  methods: {
    async loadEventDetails() {
      try {
        const apiEvent = await this.$store.dispatch('fetchEvent', this.$route.params.id)
        this.event = apiEventToView(apiEvent)
        
        // Mock attendees data - replace with actual API call
        this.attendees = (apiEvent.registrations || []).map(r => ({
          id: r.id,
          name: r.user?.name || 'Unknown',
          email: r.user?.email || 'unknown@email.com',
          checked_in: !!r.checked_in_at,
          registered_at: r.created_at || new Date().toISOString()
        }))
      } catch (error) {
        console.error('Failed to load event:', error)
        alert('Failed to load event details')
        this.$router.push('/organizer/dashboard')
      } finally {
        this.loading = false
      }
    },
    async markCheckIn(attendee) {
      try {
        await this.$store.dispatch('checkIn', attendee.id)
        attendee.checked_in = true
        alert(`${attendee.name} has been checked in successfully!`)
      } catch (error) {
        console.error('Check-in failed:', error)
        alert('Failed to check in attendee')
      }
    },
    exportAttendees() {
      // Mock CSV export - implement actual export functionality
      const csvContent = [
        ['Name', 'Email', 'Registration Date', 'Check-in Status'],
        ...this.attendees.map(a => [
          a.name,
          a.email,
          this.formatDate(a.registered_at),
          a.checked_in ? 'Checked In' : 'Registered'
        ])
      ].map(row => row.join(',')).join('\n')
      
      const blob = new Blob([csvContent], { type: 'text/csv' })
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `${this.event.title}-attendees.csv`
      a.click()
      window.URL.revokeObjectURL(url)
    },
    getStatusBadgeClass(status) {
      switch(status) {
        case 'approved': return 'bg-green-100 text-green-800'
        case 'pending': return 'bg-yellow-100 text-yellow-800'
        case 'rejected': return 'bg-red-100 text-red-800'
        default: return 'bg-gray-100 text-gray-800'
      }
    },
    formatDate(date) {
      if (!date) return 'N/A'
      return new Date(date).toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        year: 'numeric'
      })
    }
  }
}
</script>