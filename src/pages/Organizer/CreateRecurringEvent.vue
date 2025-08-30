<template>
  <div>
    <Navbar />
    <main class="max-w-4xl mx-auto p-6">
      <Breadcrumb :items="[{name: 'Home', to: '/'}, {name: 'Organizer', to: '/organizer/dashboard'}, {name: 'Create Recurring Event'}]" />
      
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Create Recurring Event</h2>
        
        <form @submit.prevent="createEvents" class="space-y-6">
          <!-- Basic Event Info -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2">Event Title</label>
              <input v-model="form.title" required class="w-full border rounded px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2">Category</label>
              <select v-model="form.category" class="w-full border rounded px-3 py-2">
                <option>Workshop</option>
                <option>Seminar</option>
                <option>Cultural</option>
                <option>Sports</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Description</label>
            <textarea v-model="form.description" class="w-full border rounded px-3 py-2 h-24"></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2">Start Date</label>
              <input v-model="form.start_date" type="date" required class="w-full border rounded px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2">Start Time</label>
              <input v-model="form.start_time" type="time" required class="w-full border rounded px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2">Duration (hours)</label>
              <input v-model="form.duration" type="number" min="1" max="12" value="2" class="w-full border rounded px-3 py-2" />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2">Location</label>
              <input v-model="form.location" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2">Capacity</label>
              <input v-model="form.capacity" type="number" min="1" class="w-full border rounded px-3 py-2" />
            </div>
          </div>

          <!-- Recurring Pattern -->
          <div class="border-t pt-6">
            <h3 class="text-lg font-semibold mb-4">Recurring Pattern</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2">Frequency</label>
                <select v-model="recurring.frequency" class="w-full border rounded px-3 py-2">
                  <option value="daily">Daily</option>
                  <option value="weekly">Weekly</option>
                  <option value="monthly">Monthly</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">Interval</label>
                <input v-model="recurring.interval" type="number" min="1" max="12" value="1" class="w-full border rounded px-3 py-2" />
                <p class="text-xs text-gray-500 mt-1">Every {{ recurring.interval }} {{ recurring.frequency.slice(0, -2) }}(s)</p>
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">Number of Events</label>
                <input v-model="recurring.occurrences" type="number" min="2" max="20" value="5" class="w-full border rounded px-3 py-2" />
              </div>
            </div>
          </div>

          <!-- Options -->
          <div class="border-t pt-6">
            <h3 class="text-lg font-semibold mb-4">Options</h3>
            <label class="flex items-center">
              <input v-model="form.enable_waitlist" type="checkbox" class="mr-3" />
              <span>Enable waitlist when event is full</span>
            </label>
          </div>

          <div class="flex justify-end pt-6">
            <button 
              type="submit" 
              :disabled="creating"
              class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 disabled:opacity-50"
            >
              {{ creating ? 'Creating Events...' : 'Create Recurring Events' }}
            </button>
          </div>
        </form>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import Breadcrumb from '@/components/Breadcrumb.vue'

export default {
  name: 'CreateRecurringEvent',
  components: { Navbar, Footer, Breadcrumb },
  data() {
    return {
      creating: false,
      form: {
        title: '',
        description: '',
        category: 'Workshop',
        start_date: '',
        start_time: '',
        duration: 2,
        location: '',
        capacity: 50,
        enable_waitlist: false
      },
      recurring: {
        frequency: 'weekly',
        interval: 1,
        occurrences: 5
      }
    }
  },
  methods: {
    async createEvents() {
      try {
        this.creating = true
        
        const response = await this.$http.post('/api/events/recurring', {
          ...this.form,
          recurring_pattern: this.recurring
        })
        
        alert(`Created ${response.data.events.length} recurring events!`)
        this.$router.push('/organizer/dashboard')
      } catch (error) {
        console.error('Failed to create recurring events:', error)
        alert('Failed to create recurring events')
      } finally {
        this.creating = false
      }
    }
  }
}
</script>