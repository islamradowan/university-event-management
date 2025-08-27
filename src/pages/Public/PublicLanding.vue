<template>
  <div>
    <Navbar />
    <header class="bg-indigo-600 text-white py-12">
      <div class="max-w-6xl mx-auto text-center px-4">
        <h1 class="text-4xl font-bold mb-2">University Event Management</h1>
        <p class="text-lg mb-6">Browse, register and manage university events.</p>
        <router-link to="/login" class="bg-white text-indigo-600 px-6 py-3 rounded-lg shadow">Get Started</router-link>
      </div>
    </header>

    <main class="max-w-6xl mx-auto p-6 grid lg:grid-cols-3 gap-6">
      <section class="lg:col-span-2">
        <h2 class="text-2xl font-semibold mb-4">Upcoming Events</h2>
        <div class="grid sm:grid-cols-2 gap-4">
          <EventCard v-for="e in events" :key="e.id" :event="e" />
        </div>
      </section>

      <aside>
        <div class="bg-white p-4 rounded shadow mb-4">
          <h4 class="font-semibold">Featured</h4>
          <div class="h-40 bg-slate-100 rounded mt-3"></div>
        </div>
        <div class="bg-white p-4 rounded shadow">
          <h4 class="font-semibold">Quick Links</h4>
          <ul class="mt-2 text-sm text-slate-600">
            <li><router-link to="/student/dashboard">Student dashboard</router-link></li>
            <li><router-link to="/organizer/dashboard">Organizer</router-link></li>
            <li><router-link to="/admin/dashboard">Admin</router-link></li>
          </ul>
        </div>
      </aside>
    </main>

    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import EventCard from '@/components/EventCard.vue'

export default {
  name: 'PublicLanding',
  components: { Navbar, Footer, EventCard },
  data() {
    return { events: [], loading: true }
  },
  async created() {
    try {
      this.events = await this.$store.dispatch('fetchPublicEvents')
    } finally {
      this.loading = false
    }
  }
}
</script>
