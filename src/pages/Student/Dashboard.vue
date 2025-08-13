<template>
  <div>
    <Navbar @search="onSearch" />
    <main class="max-w-6xl mx-auto p-6">
      <header class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Events for you</h1>
        <router-link to="/student/my-events" class="text-sm border px-3 py-1 rounded">My Events</router-link>
      </header>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <EventCard v-for="e in filtered" :key="e.id" :event="e" />
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import EventCard from '@/components/EventCard.vue'

export default {
  name: 'StudentDashboard',
  components: { Navbar, Footer, EventCard },
  data() {
    return {
      events: [
        { id:1, title:'AI Ethics Seminar', date:'2025-09-10', time:'15:00', location:'Auditorium A', description:'Ethics in AI', category:'Seminar', status:'approved' },
        { id:3, title:'Web Dev Workshop', date:'2025-08-25', time:'10:00', location:'Lab 3', description:'Hands on web dev', category:'Workshop', status:'pending' }
      ],
      q: ''
    }
  },
  computed: {
    filtered() {
      if (!this.q) return this.events
      const q = this.q.toLowerCase()
      return this.events.filter(e => (e.title+e.description+e.location).toLowerCase().includes(q))
    }
  },
  methods: {
    onSearch(q) { this.q = q }
  }
}
</script>
