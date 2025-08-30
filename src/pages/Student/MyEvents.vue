<template>
  <div class="min-h-screen bg-gray-50">
    <AppNavbar />
    
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <Breadcrumb :items="[
        {name: 'Home', to: '/'}, 
        {name: 'Dashboard', to: '/student/dashboard'}, 
        {name: 'My Events'}
      ]" />

      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">My Events</h1>
          <p class="mt-2 text-gray-600">Events you've registered for and attended</p>
        </div>
        <div class="mt-4 sm:mt-0">
          <router-link to="/student/dashboard" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Browse Events
          </router-link>
        </div>
      </div>

      <!-- Tabs -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8 px-6">
            <button
              v-for="tab in tabs"
              :key="tab.key"
              @click="activeTab = tab.key"
              :class="[
                activeTab === tab.key
                  ? 'border-indigo-500 text-indigo-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors'
              ]"
            >
              {{ tab.name }}
              <span v-if="tab.count !== undefined" class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs font-medium">
                {{ tab.count }}
              </span>
            </button>
          </nav>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
        <p class="text-gray-600 mt-4">Loading your events...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="filteredEvents.length === 0" class="text-center py-12">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No events found</h3>
        <p class="text-gray-600 mb-6">
          {{ activeTab === 'upcoming' ? "You haven't registered for any upcoming events yet." : "You haven't attended any events yet." }}
        </p>
        <router-link to="/student/dashboard" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          Browse Events
        </router-link>
      </div>

      <!-- Events Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="event in filteredEvents" :key="event.id" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow group">
          <!-- Event Image -->
          <div class="relative h-48 overflow-hidden">
            <img 
              v-if="event.poster" 
              :src="event.poster" 
              :alt="event.title"
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
              <span v-if="isPastEvent(event)" class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-medium">
                Completed
              </span>
              <span v-else class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                Registered
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
                {{ formatDate(event.date || event.start_at) }}
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
                v-if="!isPastEvent(event)"
                @click="showQR(event)" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center"
              >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 16h4.01M12 8h4.01"></path>
                </svg>
                QR Code
              </button>
              
              <button 
                v-if="isPastEvent(event)"
                @click="showFeedback(event)" 
                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center"
              >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
                Feedback
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- QR Code Modal -->
    <Modal :visible="showQRModal" @close="showQRModal = false" title="Your QR Code">
      <div v-if="selectedEvent" class="text-center">
        <QRCodeDisplay :event-id="selectedEvent.id" />
        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
          <h4 class="font-medium text-gray-900 mb-2">{{ selectedEvent.title }}</h4>
          <p class="text-sm text-gray-600">Show this QR code at the event for check-in</p>
        </div>
      </div>
    </Modal>

    <!-- Feedback Modal -->
    <Modal :visible="showFeedbackModal" @close="showFeedbackModal = false" title="Leave Feedback">
      <div v-if="selectedEvent">
        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
          <h4 class="font-medium text-gray-900">{{ selectedEvent.title }}</h4>
          <p class="text-sm text-gray-600">{{ formatDate(selectedEvent.date || selectedEvent.start_at) }}</p>
        </div>
        
        <form @submit.prevent="submitFeedback" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Rating</label>
            <div class="flex space-x-1">
              <button 
                v-for="star in 5" 
                :key="star"
                type="button"
                @click="feedbackForm.rating = star"
                class="text-3xl focus:outline-none transition-colors"
                :class="star <= feedbackForm.rating ? 'text-yellow-400' : 'text-gray-300'"
              >
                ★
              </button>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Comment (optional)</label>
            <textarea 
              v-model="feedbackForm.comment" 
              class="w-full border border-gray-300 rounded-lg px-3 py-2 h-24 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              placeholder="Share your thoughts about this event..."
            ></textarea>
          </div>
          <div class="flex justify-end space-x-3">
            <button type="button" @click="showFeedbackModal = false" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
              Cancel
            </button>
            <button 
              type="submit" 
              :disabled="submittingFeedback"
              class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition-colors disabled:opacity-50 flex items-center"
            >
              <svg v-if="submittingFeedback" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ submittingFeedback ? 'Submitting...' : 'Submit Feedback' }}
            </button>
          </div>
        </form>
      </div>
    </Modal>
  </div>
</template>

<script>
import AppNavbar from '@/components/AppNavbar.vue'
import Breadcrumb from '@/components/Breadcrumb.vue'
import Modal from '@/components/Modal.vue'
import QRCodeDisplay from '@/components/QRCodeDisplay.vue'
import { apiEventToView } from '@/utils/apiMappers'

export default {
  name: 'MyEvents',
  components: { AppNavbar, Breadcrumb, Modal, QRCodeDisplay },
  data() { 
    return { 
      myEvents: [],
      loading: true,
      activeTab: 'upcoming',
      showQRModal: false,
      showFeedbackModal: false,
      selectedEvent: null,
      feedbackForm: { rating: 5, comment: '' },
      submittingFeedback: false
    } 
  },
  async created() {
    await this.loadMyEvents()
  },
  computed: {
    tabs() {
      const upcoming = this.myEvents.filter(e => !this.isPastEvent(e))
      const past = this.myEvents.filter(e => this.isPastEvent(e))
      
      return [
        { key: 'upcoming', name: 'Upcoming', count: upcoming.length },
        { key: 'past', name: 'Past Events', count: past.length }
      ]
    },
    filteredEvents() {
      if (this.activeTab === 'upcoming') {
        return this.myEvents.filter(e => !this.isPastEvent(e))
      } else {
        return this.myEvents.filter(e => this.isPastEvent(e))
      }
    }
  },
  methods: {
    async loadMyEvents() {
      try {
        const regs = await this.$store.dispatch('fetchMyRegistrations')
        this.myEvents = regs.map(r => apiEventToView(r.event))
      } catch (error) {
        console.error('Failed to load events:', error)
      } finally {
        this.loading = false
      }
    },
    showQR(event) {
      this.selectedEvent = event
      this.showQRModal = true
    },
    showFeedback(event) {
      this.selectedEvent = event
      this.showFeedbackModal = true
      this.feedbackForm = { rating: 5, comment: '' }
    },
    async submitFeedback() {
      try {
        this.submittingFeedback = true
        await this.$http.post(`/api/events/${this.selectedEvent.id}/feedback`, {
          rating: this.feedbackForm.rating,
          comment: this.feedbackForm.comment
        })
        this.showFeedbackModal = false
        alert('Feedback submitted successfully!')
      } catch (error) {
        console.error('Failed to submit feedback:', error)
        alert('Failed to submit feedback')
      } finally {
        this.submittingFeedback = false
      }
    },
    isPastEvent(event) {
      if (!event?.start_at && !event?.date) return false
      const eventDate = new Date(event.start_at || event.date)
      return eventDate < new Date()
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

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>