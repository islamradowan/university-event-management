<template>
  <div>
    <Navbar />
    <main class="max-w-4xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-2">{{ event.title }}</h1>
      <p class="text-sm text-slate-600">{{ event.date }} · {{ event.location }}</p>

      <section class="bg-white p-4 rounded shadow mt-4">
        <h3 class="font-semibold mb-2">Attendees</h3>
        <table class="w-full text-left text-sm">
          <thead><tr><th>Name</th><th>Email</th><th>Checked in</th></tr></thead>
          <tbody>
            <tr v-for="a in attendees" :key="a.email">
              <td>{{ a.name }}</td><td>{{ a.email }}</td><td>{{ a.checked_in ? 'Yes':'No' }}</td>
            </tr>
          </tbody>
        </table>
      </section>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import { apiEventToView } from '@/utils/apiMappers'

export default {
  name: 'OrganizerEventDetails',
  components: { Navbar, Footer },
  data() {
    return { event: null, attendees: [] }
  },
  async created() {
    const apiEvent = await this.$store.dispatch('fetchEvent', this.$route.params.id)
    this.event = apiEventToView(apiEvent)
    // assumes backend loads registrations with user relationship
    this.attendees = (apiEvent.registrations || []).map(r => ({
      name: r.user?.name || '-',
      email: r.user?.email || '-',
      checked_in: !!r.checked_in_at,
      id: r.id
    }))
  },
  methods: {
    async markCheckIn(reg) {
      await this.$store.dispatch('checkIn', reg.id)
      reg.checked_in = true
    }
  }
}
</script>
