<template>
  <div class="min-h-screen bg-gray-50">
    <AppNavbar />
    
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <Breadcrumb :items="[
        {name: 'Home', to: '/'}, 
        {name: 'Dashboard', to: '/student/dashboard'}, 
        {name: event?.title || 'Event Details'}
      ]" />

      <div v-if="!event" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
        <p class="text-gray-600 mt-4">Loading event details...</p>
      </div>

      <div v-else class="space-y-8">
        <!-- Event Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <!-- Event Image -->
          <div class="h-64 sm:h-80 relative">
            <img 
              v-if="event.poster" 
              :src="event.poster" 
              :alt="event.title"
              class="w-full h-full object-cover"
            />
            <div 
              v-else 
              class="w-full h-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center"
            >
              <svg class="w-24 h-24 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            
            <!-- Status Badge -->
            <div class="absolute top-4 right-4">
              <span :class="getStatusClass(event.status)" class="px-3 py-1 rounded-full text-sm font-medium">
                {{ event.status }}
              </span>
            </div>
          </div>

          <!-- Event Info -->
          <div class="p-8">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
              <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ event.title }}</h1>
                
                <!-- Event Meta -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                  <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ formatDate(event.date || event.start_at) }}</span>
                  </div>
                  
                  <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ formatTime(event.time || event.start_at) }}</span>
                  </div>
                  
                  <div v-if="event.location" class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ event.location }}</span>
                  </div>
                  
                  <div v-if="event.category" class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span>{{ event.category }}</span>
                  </div>
                </div>

                <!-- Description -->
                <div class="prose max-w-none">
                  <p class="text-gray-700 leading-relaxed">{{ event.description || 'No description available.' }}</p>
                </div>
              </div>

              <!-- Action Panel -->
              <div class="mt-8 lg:mt-0 lg:ml-8 lg:flex-shrink-0">
                <div class="bg-gray-50 rounded-xl p-6 space-y-4">
                  <!-- Registration Status -->
                  <div v-if="isRegistered" class="flex items-center text-green-600 bg-green-50 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">You're registered!</span>
                  </div>

                  <!-- Waitlist Status -->
                  <div v-else-if="isOnWaitlist" class="flex items-center text-yellow-600 bg-yellow-50 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">On waitlist (Position: {{ waitlistPosition }})</span>
                  </div>

                  <!-- Action Buttons -->
                  <div class="space-y-3">
                    <button 
                      v-if="!isRegistered && !isEventFull" 
                      @click="register" 
                      :disabled="registering"
                      class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                    >
                      <svg v-if="registering" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      {{ registering ? 'Registering...' : 'Register for Event' }}
                    </button>

                    <button 
                      v-if="!isRegistered && isEventFull && event.enable_waitlist && !isOnWaitlist" 
                      @click="joinWaitlist" 
                      :disabled="joiningWaitlist"
                      class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                    >
                      <svg v-if="joiningWaitlist" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      {{ joiningWaitlist ? 'Joining...' : 'Join Waitlist' }}
                    </button>

                    <button 
                      v-if="isRegistered" 
                      @click="showQRModal = true" 
                      class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center justify-center"
                    >
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 16h4.01M12 8h4.01"></path>
                      </svg>
                      Show QR Code
                    </button>

                    <button 
                      v-if="isRegistered && isPastEvent" 
                      @click="showFeedbackModal = true" 
                      class="w-full bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center justify-center"
                    >
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                      </svg>
                      Leave Feedback
                    </button>
                  </div>

                  <!-- Event Stats -->
                  <div class="pt-4 border-t border-gray-200 space-y-2">
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-600">Registered</span>
                      <span class="font-medium">{{ event.registrations?.length || 0 }}</span>
                    </div>
                    <div v-if="event.capacity" class="flex justify-between text-sm">
                      <span class="text-gray-600">Capacity</span>
                      <span class="font-medium">{{ event.capacity }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Gallery -->
        <div v-if="galleryImages.length > 0" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-6">Gallery</h2>
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div v-for="image in galleryImages" :key="image.id" class="aspect-square rounded-xl overflow-hidden">
              <img :src="image.url" :alt="`Gallery image ${image.id}`" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- QR Code Modal -->
    <Modal :visible="showQRModal" @close="showQRModal = false" title="Your QR Code">
      <div v-if="event" class="text-center">
        <QRCodeDisplay :event-id="event.id" />
        <p class="text-sm text-gray-600 mt-4">Show this QR code at the event for check-in</p>
      </div>
    </Modal>
    
    <!-- Feedback Modal -->
    <Modal :visible="showFeedbackModal" @close="showFeedbackModal = false" title="Leave Feedback">
      <div v-if="event">
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
  name: 'StudentEventDetails',
  components: { AppNavbar, Breadcrumb, Modal, QRCodeDisplay },
  data() {
    return { 
      event: null,
      isRegistered: false,
      showQRModal: false,
      registering: false,
      isOnWaitlist: false,
      waitlistPosition: 0,
      joiningWaitlist: false,
      showFeedbackModal: false,
      feedbackForm: { rating: 5, comment: '' },
      submittingFeedback: false
    }
  },
  async created() {
    const apiEvent = await this.$store.dispatch('fetchEvent', this.$route.params.id)
    this.event = apiEventToView(apiEvent)
    await this.checkRegistrationStatus()
  },
  computed: {
    galleryImages() {
      if (!this.event?.media) return []
      return this.event.media
        .filter(m => m.type === 'gallery')
        .map(m => ({
          id: m.id,
          url: `http://localhost:8000/storage/${m.file_path}`
        }))
    },
    isEventFull() {
      if (!this.event?.capacity) return false
      return (this.event.registrations?.length || 0) >= this.event.capacity
    },
    isPastEvent() {
      if (!this.event?.start_at) return false
      return new Date(this.event.start_at) < new Date()
    }
  },
  methods: {
    async checkRegistrationStatus() {
      try {
        const registrations = await this.$store.dispatch('fetchMyRegistrations')
        this.isRegistered = registrations.some(reg => reg.event_id == this.event.id)
        
        if (!this.isRegistered && this.event.enable_waitlist) {
          await this.checkWaitlistStatus()
        }
      } catch (err) {
        console.error('Failed to check registration status:', err)
      }
    },
    async checkWaitlistStatus() {
      try {
        const response = await this.$http.get(`/api/events/${this.event.id}/waitlist`)
        const userWaitlist = response.data.find(w => w.user_id === this.$store.state.user?.id)
        if (userWaitlist) {
          this.isOnWaitlist = true
          this.waitlistPosition = userWaitlist.position
        }
      } catch (err) {
        console.error('Failed to check waitlist status:', err)
      }
    },
    async register() {
      try {
        this.registering = true
        console.log('Attempting to register for event:', this.event.id)
        console.log('User:', this.$store.state.user)
        await this.$store.dispatch('registerForEvent', this.event.id)
        this.isRegistered = true
        alert('Registered successfully! Check "My Events" for your QR code.')
      } catch (err) {
        console.error('Registration failed:', err)
        console.error('Error details:', err.response?.data)
        alert(`Registration failed: ${err.response?.data?.message || 'Please try again.'}`)
      } finally {
        this.registering = false
      }
    },
    async joinWaitlist() {
      try {
        this.joiningWaitlist = true
        const response = await this.$http.post(`/api/events/${this.event.id}/waitlist/join`)
        this.isOnWaitlist = true
        this.waitlistPosition = response.data.position
        alert(`Added to waitlist! You are position #${response.data.position}`)
      } catch (err) {
        console.error('Failed to join waitlist:', err)
        alert('Failed to join waitlist')
      } finally {
        this.joiningWaitlist = false
      }
    },
    async submitFeedback() {
      try {
        this.submittingFeedback = true
        await this.$http.post(`/api/events/${this.event.id}/feedback`, {
          rating: this.feedbackForm.rating,
          comment: this.feedbackForm.comment
        })
        this.showFeedbackModal = false
        this.feedbackForm = { rating: 5, comment: '' }
        alert('Feedback submitted successfully!')
      } catch (error) {
        console.error('Failed to submit feedback:', error)
        alert('Failed to submit feedback')
      } finally {
        this.submittingFeedback = false
      }
    },
    formatDate(date) {
      if (!date) return 'TBD'
      return new Date(date).toLocaleDateString('en-US', { 
        weekday: 'long',
        year: 'numeric',
        month: 'long', 
        day: 'numeric'
      })
    },
    formatTime(time) {
      if (!time) return 'TBD'
      if (typeof time === 'string' && time.includes(':')) {
        return time
      }
      return new Date(time).toLocaleTimeString('en-US', { 
        hour: 'numeric', 
        minute: '2-digit',
        hour12: true
      })
    },
    getStatusClass(status) {
      const classes = {
        approved: 'bg-green-100 text-green-800',
        pending: 'bg-yellow-100 text-yellow-800',
        rejected: 'bg-red-100 text-red-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }
  }
}
</script>