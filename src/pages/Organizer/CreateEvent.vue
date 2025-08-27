<template>
  <div>
    <Navbar />
    <main class="max-w-3xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Create Event</h1>
      <form @submit.prevent="create" class="bg-white p-4 rounded shadow space-y-3">
        <input v-model="form.title" placeholder="Event title" required class="w-full border rounded px-3 py-2" />
        <div class="grid grid-cols-2 gap-2">
          <input v-model="form.date" type="date" required class="border rounded px-3 py-2" />
          <input v-model="form.time" type="time" required class="border rounded px-3 py-2" />
        </div>
        <input v-model="form.location" placeholder="Location" class="w-full border rounded px-3 py-2" />
        <select v-model="form.category" class="w-full border rounded px-3 py-2">
          <option>Workshop</option><option>Seminar</option><option>Cultural</option>
        </select>
        <textarea v-model="form.description" placeholder="Description" class="w-full border rounded px-3 py-2 h-32"></textarea>
        <div class="flex justify-end">
          <button class="bg-indigo-600 text-white px-4 py-2 rounded">Submit for Approval</button>
        </div>
      </form>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import { formToEventPayload } from '@/utils/apiMappers'

export default {
  name: 'CreateEvent',
  components: { Navbar, Footer },
  data() {
    return { form: { title:'', date:'', time:'', location:'', category:'Workshop', description:'' } }
  },
  methods: {
    async create() {
      try {
        await this.$http.post('/api/events', formToEventPayload(this.form))
        alert('Event created')
        this.$router.push('/organizer/dashboard')
      } catch (err) {
        console.error(err)
        alert('Create failed')
      }
    }
  }
}
</script>
