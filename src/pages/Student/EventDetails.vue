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

export default {
  name: 'StudentEventDetails',
  components: { Navbar, Footer },
  data() {
    return { event: { id:this.$route.params.id, title:'Loading...', date:'', time:'', location:'', description:'' } }
  },
  created() {
    // TODO: fetch /api/events/:id
    // demo payload:
    this.event = { id:this.$route.params.id, title:'AI Ethics Seminar', date:'2025-09-10', time:'15:00', location:'Auditorium A', description:'Long description...' }
  },
  methods: {
    async register() {
      try {
        // POST /api/events/:id/register
        await this.$http.post(`/api/events/${this.event.id}/register`)
        alert('Registered (demo)')
      } catch (err) {
        console.error(err); alert('Register failed (demo)')
      }
    },
    addCalendar() { alert('Add to calendar (demo)') }
  }
}
</script>
