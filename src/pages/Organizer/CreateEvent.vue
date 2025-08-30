<template>
  <div class="min-h-screen bg-gray-50">
    <AppNavbar />
    
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <Breadcrumb :items="[
        {name: 'Home', to: '/'}, 
        {name: 'Dashboard', to: '/organizer/dashboard'}, 
        {name: 'Create Event'}
      ]" />

      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create New Event</h1>
        <p class="mt-2 text-gray-600">Fill in the details to create an amazing event experience</p>
      </div>

      <!-- Form -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form @submit.prevent="create" class="divide-y divide-gray-200">
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

          <!-- Media Upload -->
          <div class="p-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Event Poster</h3>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-indigo-400 transition-colors">
              <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <div class="mt-4">
                <label class="cursor-pointer">
                  <span class="mt-2 block text-sm font-medium text-gray-900">Upload event poster</span>
                  <span class="mt-1 block text-sm text-gray-500">PNG, JPG, GIF up to 2MB</span>
                  <input type="file" class="sr-only" accept="image/*" @change="handlePosterUpload">
                </label>
              </div>
            </div>
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
                {{ submitting ? 'Creating...' : 'Submit for Approval' }}
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
import { formToEventPayload } from '@/utils/apiMappers'

export default {
  name: 'CreateEvent',
  components: { AppNavbar, Breadcrumb },
  data() {
    return { 
      form: { 
        title: '', 
        description: '',
        date: '', 
        time: '', 
        endDate: '',
        endTime: '',
        location: '', 
        category: 'Workshop',
        capacity: null,
        poster: null
      },
      submitting: false
    }
  },
  methods: {
    async create() {
      try {
        this.submitting = true
        await this.$http.post('/api/events', formToEventPayload(this.form))
        alert('Event created successfully! It will be reviewed by administrators.')
        this.$router.push('/organizer/dashboard')
      } catch (err) {
        console.error(err)
        alert('Failed to create event. Please try again.')
      } finally {
        this.submitting = false
      }
    },
    async saveDraft() {
      try {
        const payload = { ...formToEventPayload(this.form), status: 'draft' }
        await this.$http.post('/api/events', payload)
        alert('Event saved as draft')
        this.$router.push('/organizer/dashboard')
      } catch (err) {
        console.error(err)
        alert('Failed to save draft')
      }
    },
    handlePosterUpload(event) {
      const file = event.target.files[0]
      if (file) {
        this.form.poster = file
      }
    }
  }
}
</script>