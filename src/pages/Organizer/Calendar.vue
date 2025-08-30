<template>
  <div>
    <Navbar />
    <main class="max-w-7xl mx-auto p-6">
      <Breadcrumb :items="[{name: 'Home', to: '/'}, {name: 'Organizer', to: '/organizer/dashboard'}, {name: 'Calendar'}]" />
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">My Events Calendar</h1>

      </div>

      <div class="mb-4 flex gap-4 text-sm">
        <div class="flex items-center gap-2">
          <div class="w-4 h-4 bg-green-500 rounded"></div>
          <span>Approved</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-4 h-4 bg-yellow-500 rounded"></div>
          <span>Pending</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-4 h-4 bg-red-500 rounded"></div>
          <span>Rejected</span>
        </div>
      </div>

      <EventCalendar 
        :events="events" 
        @event-click="handleEventClick"
        @date-click="handleDateClick"
      />

      <!-- Event Details Modal -->
      <Modal :visible="showEventModal" @close="showEventModal = false">
        <div v-if="selectedEvent">
          <h2 class="text-xl font-bold mb-4">{{ selectedEvent.title }}</h2>
          <div class="space-y-2 text-sm">
            <p><strong>Date:</strong> {{ formatDate(selectedEvent.start) }}</p>
            <p v-if="selectedEvent.end"><strong>End:</strong> {{ formatDate(selectedEvent.end) }}</p>
            <p v-if="selectedEvent.location"><strong>Location:</strong> {{ selectedEvent.location }}</p>
            <p v-if="selectedEvent.category"><strong>Category:</strong> {{ selectedEvent.category }}</p>
            <p><strong>Status:</strong> 
              <span :class="getStatusClass(selectedEvent.status)">{{ selectedEvent.status }}</span>
            </p>
            <p v-if="selectedEvent.description"><strong>Description:</strong> {{ selectedEvent.description }}</p>
          </div>
          <div class="mt-4 flex gap-2">
            <router-link 
              :to="`/organizer/event/${selectedEvent.id}`" 
              class="bg-indigo-600 text-white px-4 py-2 rounded text-sm"
            >
              Manage Event
            </router-link>
            <router-link 
              :to="`/organizer/edit/${selectedEvent.id}`" 
              class="border px-4 py-2 rounded text-sm"
            >
              Edit
            </router-link>
            <button 
              @click="showEventModal = false" 
              class="border px-4 py-2 rounded text-sm"
            >
              Close
            </button>
          </div>
        </div>
      </Modal>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import EventCalendar from '@/components/EventCalendar.vue'
import Modal from '@/components/Modal.vue'
import Breadcrumb from '@/components/Breadcrumb.vue'

export default {
  name: 'OrganizerCalendar',
  components: { Navbar, Footer, EventCalendar, Modal, Breadcrumb },
  data() {
    return {
      events: [],
      loading: true,
      showEventModal: false,
      selectedEvent: null
    }
  },
  async created() {
    await this.loadEvents()
  },
  methods: {
    async loadEvents() {
      try {
        this.loading = true
        this.events = await this.$store.dispatch('fetchMyEvents')
      } catch (error) {
        console.error('Failed to load events:', error)
      } finally {
        this.loading = false
      }
    },
    handleEventClick(event) {
      this.selectedEvent = event
      this.showEventModal = true
    },
    handleDateClick(date) {
      // Navigate to create event with pre-selected date
      this.$router.push({
        path: '/organizer/create',
        query: { date: date.toISOString().split('T')[0] }
      })
    },
    formatDate(date) {
      return new Date(date).toLocaleString()
    },
    getStatusClass(status) {
      switch (status) {
        case 'approved': return 'text-green-600 font-medium'
        case 'pending': return 'text-yellow-600 font-medium'
        case 'rejected': return 'text-red-600 font-medium'
        default: return 'text-gray-600'
      }
    }
  }
}
</script>