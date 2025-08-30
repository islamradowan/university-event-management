<template>
  <div>
    <Navbar />
    <main class="max-w-7xl mx-auto p-6">
      <Breadcrumb :items="[{name: 'Home', to: '/'}, {name: 'Admin', to: '/admin/dashboard'}, {name: 'Calendar'}]" />
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">All Events Calendar</h1>
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
            <p v-if="selectedEvent.organizer"><strong>Organizer:</strong> {{ selectedEvent.organizer.name }}</p>
            <p v-if="selectedEvent.description"><strong>Description:</strong> {{ selectedEvent.description }}</p>
          </div>
          <div class="mt-4 flex gap-2">
            <button 
              v-if="selectedEvent.status === 'pending'"
              @click="approveEvent(selectedEvent.id)" 
              class="bg-green-600 text-white px-4 py-2 rounded text-sm"
            >
              Approve
            </button>
            <button 
              v-if="selectedEvent.status === 'pending'"
              @click="rejectEvent(selectedEvent.id)" 
              class="bg-red-600 text-white px-4 py-2 rounded text-sm"
            >
              Reject
            </button>
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
  name: 'AdminCalendar',
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
        // Load all events including pending ones for admin
        const [approvedEvents, pendingEvents] = await Promise.all([
          this.$store.dispatch('fetchEvents'),
          this.$store.dispatch('fetchPendingEvents')
        ])
        this.events = [...approvedEvents, ...pendingEvents]
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
      console.log('Date clicked:', date)
    },
    async approveEvent(eventId) {
      try {
        await this.$http.put(`/api/events/${eventId}/approve`)
        this.selectedEvent.status = 'approved'
        await this.loadEvents()
        alert('Event approved successfully')
      } catch (error) {
        console.error('Failed to approve event:', error)
        alert('Failed to approve event')
      }
    },
    async rejectEvent(eventId) {
      try {
        await this.$http.put(`/api/events/${eventId}/reject`)
        this.selectedEvent.status = 'rejected'
        await this.loadEvents()
        alert('Event rejected successfully')
      } catch (error) {
        console.error('Failed to reject event:', error)
        alert('Failed to reject event')
      }
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