<template>
  <div>
    <Navbar />
    <main class="max-w-4xl mx-auto p-6">
      <div class="bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold">{{ event.title }}</h1>
        <p class="text-sm text-slate-600">{{ event.date }} · {{ event.time }} · {{ event.location }}</p>
        <div class="h-48 bg-slate-100 rounded my-4"></div>
        <p class="text-slate-700">{{ event.description }}</p>
        <div class="mt-6 flex gap-3">
          <button @click="register" class="bg-indigo-600 text-white px-4 py-2 rounded">Register</button>
          <button @click="addCalendar" class="px-4 py-2 border rounded">Add to calendar</button>
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
  name: 'StudentEventDetails',
  components: { Navbar, Footer },
  data() {
     return { event: null }
  },
  async created() {
    const apiEvent = await this.$store.dispatch('fetchEvent', this.$route.params.id)
    this.event = apiEventToView(apiEvent)
  },
  methods: {
    async register() {
      try {
        await this.$store.dispatch('registerForEvent', this.event.id)
        alert('Registered! Check “My Events”.')
      } catch (err) {
        console.error(err); alert('Register failed')
      }
    },
    addCalendar() { /* optional */ }
  }
}
</script>
