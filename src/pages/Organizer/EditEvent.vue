<template>
  <div>
    <Navbar />
    <main class="max-w-3xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Edit Event</h1>
      <form @submit.prevent="save" class="bg-white p-4 rounded shadow space-y-3">
        <input v-model="form.title" required class="w-full border rounded px-3 py-2" />
        <div class="grid grid-cols-2 gap-2">
          <input v-model="form.date" type="date" required class="border rounded px-3 py-2" />
          <input v-model="form.time" type="time" required class="border rounded px-3 py-2" />
        </div>
        <input v-model="form.location" class="w-full border rounded px-3 py-2" />
        <select v-model="form.category" class="w-full border rounded px-3 py-2">
          <option>Workshop</option><option>Seminar</option><option>Cultural</option>
        </select>
        <textarea v-model="form.description" class="w-full border rounded px-3 py-2 h-32"></textarea>
        <div class="flex justify-between">
          <button class="px-4 py-2 border rounded">Save draft</button>
          <button class="bg-indigo-600 text-white px-4 py-2 rounded">Save changes</button>
        </div>
      </form>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import { apiEventToView, formToEventPayload } from '@/utils/apiMappers'

export default {
  name: 'EditEvent',
  components: { Navbar, Footer },
  data() {
    return { form: { title:'', date:'', time:'', location:'', category:'Workshop', description:'' } }
  },
  async created() {
    const apiEvent = await this.$store.dispatch('fetchEvent', this.$route.params.id)
    const view = apiEventToView(apiEvent)
    this.form = {
      title: view.title,
      date: view.date,
      time: view.time,
      location: view.location || '',
      category: view.category || 'Workshop',
      description: view.description || ''
    }
  },
  methods: {
    async save() {
      try {
        const payload = formToEventPayload(this.form)
        await this.$store.dispatch('updateEvent', { id: this.$route.params.id, payload })
        alert('Saved')
        this.$router.push('/organizer/dashboard')
      } catch (err) { console.error(err); alert('Save failed') }
    }
  }
}
</script>
