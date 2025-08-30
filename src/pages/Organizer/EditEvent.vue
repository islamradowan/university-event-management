<template>
  <div class="min-h-screen bg-gray-50">
    <AppNavbar />
    
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <Breadcrumb :items="[
        {name: 'Home', to: '/'}, 
        {name: 'Dashboard', to: '/organizer/dashboard'}, 
        {name: 'Edit Event'}
      ]" />

      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Event</h1>
        <p class="mt-2 text-gray-600">Update your event details</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
        <p class="text-gray-600 mt-4">Loading event details...</p>
      </div>

      <!-- Form -->
      <div v-else class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form @submit.prevent="save" class="divide-y divide-gray-200">
          <!-- Basic Information -->
          <div class="p-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Basic Information</h3>
            <div class="grid grid-cols-1 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Event Title *</label>
                <input 
                  v-model="form.title" 
                  type="text"
                  placeholder="Enter event title" 
                  required 
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea 
                  v-model="form.description" 
                  placeholder="Describe your event..." 
                  rows="4"
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors resize-none"
                ></textarea>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                  <select 
                    v-model="form.category" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                  >
                    <option value="Workshop">Workshop</option>
                    <option value="Seminar">Seminar</option>
                    <option value="Cultural">Cultural</option>
                    <option value="Sports">Sports</option>
                    <option value="Competition">Competition</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Capacity</label>
                  <input 
                    v-model="form.capacity" 
                    type="number"
                    placeholder="Max attendees" 
                    min="1"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Date & Time -->
          <div class="p-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Date & Time</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                <input 
                  v-model="form.date" 
                  type="date" 
                  required 
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                <input 
                  v-model="form.time" 
                  type="time" 
                  required 
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input 
                  v-model="form.endDate" 
                  type="date" 
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                <input 
                  v-model="form.endTime" 
                  type="time" 
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                />
              </div>
            </div>
          </div>

          <!-- Location -->
          <div class="p-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Location</h3>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Venue</label>
              <input 
                v-model="form.location" 
                type="text"
                placeholder="Enter event location" 
                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
              />
            </div>
          </div>

          <!-- Status -->
          <div class="p-8" v-if="event">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Event Status</h3>
            <div class="flex items-center space-x-4">
              <span class="text-sm font-medium text-gray-700">Current Status:</span>
              <span :class="getStatusBadgeClass(event.status)" class="px-3 py-1 rounded-full text-xs font-medium">
                {{ event.status || 'Draft' }}
              </span>
            </div>
            <p v-if="event.status === 'pending'" class="mt-2 text-sm text-yellow-600">
              Your event is pending approval from administrators.
            </p>
            <p v-if="event.status === 'approved'" class="mt-2 text-sm text-green-600">
              Your event has been approved and is visible to students.
            </p>
            <p v-if="event.status === 'rejected'" class="mt-2 text-sm text-red-600">
              Your event was rejected. Please review and resubmit.
            </p>
          </div>

          <!-- Actions -->
          <div class="px-8 py-6 bg-gray-50 flex flex-col sm:flex-row sm:justify-between space-y-3 sm:space-y-0 sm:space-x-3">
            <router-link 
              to="/organizer/dashboard" 
              class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg font-medium transition-colors text-center"
            >
              Cancel
            </router-link>
            
            <div class="flex space-x-3">
              <button 
                type="button"
                @click="saveDraft"
                class="flex-1 sm:flex-none px-6 py-3 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg font-medium transition-colors"
              >
                Save Draft
              </button>
              
              <button 
                type="submit"
                :disabled="submitting"
                class="flex-1 sm:flex-none bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 disabled:opacity-50 flex items-center justify-center"
              >
                <svg v-if="submitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ submitting ? 'Saving...' : 'Save Changes' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>

<script>
import AppNavbar from '@/components/AppNavbar.vue'
import Breadcrumb from '@/components/Breadcrumb.vue'
import { apiEventToView, formToEventPayload } from '@/utils/apiMappers'

export default {
  name: 'EditEvent',
  components: { AppNavbar, Breadcrumb },
  data() {
    return { 
      event: null,
      form: { 
        title: '', 
        description: '',
        date: '', 
        time: '', 
        endDate: '',
        endTime: '',
        location: '', 
        category: 'Workshop',
        capacity: null
      },
      loading: true,
      submitting: false
    }
  },
  async created() {
    await this.loadEvent()
  },
  methods: {
    async loadEvent() {
      try {
        const apiEvent = await this.$store.dispatch('fetchEvent', this.$route.params.id)
        this.event = apiEvent
        const view = apiEventToView(apiEvent)
        
        this.form = {
          title: view.title || '',
          description: view.description || '',
          date: view.date || '',
          time: view.time || '',
          endDate: view.endDate || '',
          endTime: view.endTime || '',
          location: view.location || '',
          category: view.category || 'Workshop',
          capacity: view.capacity || null
        }
      } catch (err) {
        console.error(err)
        alert('Failed to load event')
        this.$router.push('/organizer/dashboard')
      } finally {
        this.loading = false
      }
    },
    async save() {
      try {
        this.submitting = true
        const payload = formToEventPayload(this.form)
        await this.$store.dispatch('updateEvent', { id: this.$route.params.id, payload })
        alert('Event updated successfully!')
        this.$router.push('/organizer/dashboard')
      } catch (err) { 
        console.error(err)
        alert('Failed to save changes')
      } finally {
        this.submitting = false
      }
    },
    async saveDraft() {
      try {
        const payload = { ...formToEventPayload(this.form), status: 'draft' }
        await this.$store.dispatch('updateEvent', { id: this.$route.params.id, payload })
        alert('Event saved as draft')
        this.$router.push('/organizer/dashboard')
      } catch (err) {
        console.error(err)
        alert('Failed to save draft')
      }
    },
    getStatusBadgeClass(status) {
      switch(status) {
        case 'approved': return 'bg-green-100 text-green-800'
        case 'pending': return 'bg-yellow-100 text-yellow-800'
        case 'rejected': return 'bg-red-100 text-red-800'
        default: return 'bg-gray-100 text-gray-800'
      }
    }
  }
}
</script>