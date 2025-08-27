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
import { apiEventToView } from '@/utils/apiMappers'

export default {
  name: 'StudentDashboard',
  components: { Navbar, Footer, EventCard },
  data() {
    return { events: [], q: '' }
  },
  async created() {
    const apiEvents = await this.$store.dispatch('fetchEvents')
    this.events = apiEvents.map(apiEventToView)
    console.log(this.events)
  },
  computed: {
    filtered() {
      if (!this.q) return this.events
      const q = this.q.toLowerCase()
      return this.events.filter(e => (e.title + e.description + e.location).toLowerCase().includes(q))
    }
  },
  methods: { onSearch(q) { this.q = q } }
}
</script>
