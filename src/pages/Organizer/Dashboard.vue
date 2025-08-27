<template>
  <div>
    <Navbar />
    <main class="max-w-6xl mx-auto p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Your Events</h1>
        <router-link to="/organizer/create" class="text-sm border px-3 py-1 rounded">Create Event</router-link>
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        <div v-for="e in events" :key="e.id" class="bg-white p-4 rounded shadow">
          <h3 class="font-semibold">{{ e.title }}</h3>
          <p class="text-sm text-slate-600">{{ e.date }} · {{ e.location }}</p>
          <div class="mt-3 flex gap-2">
            <router-link :to="`/organizer/event/${e.id}`" class="text-sm border px-3 py-1 rounded">Manage</router-link>
            <router-link :to="`/organizer/edit/${e.id}`" class="text-sm border px-3 py-1 rounded">Edit</router-link>
          </div>
        </div>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import { apiEventToView } from '@/utils/apiMappers'

export default {
  name: 'OrganizerDashboard',
  components: { Navbar, Footer },
  data() {
    return { events: [] }
  },
  async created() {
    try {
      const apiEvents = await this.$store.dispatch('fetchMyEvents')
      this.events = apiEvents.map(apiEventToView)
    } catch (err) {
      console.error(err)
      alert('Failed to load events')
    }
  }
}
</script>
