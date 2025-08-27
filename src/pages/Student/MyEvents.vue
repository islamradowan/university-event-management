<template>
  <div>
    <Navbar />
    <main class="max-w-6xl mx-auto p-6">
      <h2 class="text-xl font-semibold mb-4">Events you registered for</h2>
      <div class="grid sm:grid-cols-2 gap-4">
        <div v-for="e in myEvents" :key="e.id" class="bg-white p-4 rounded shadow">
          <h3 class="font-semibold">{{ e.title }}</h3>
          <p class="text-sm text-slate-600">{{ e.date }} · {{ e.location }}</p>
          <div class="mt-3 flex justify-between items-center">
            <router-link :to="`/student/event/${e.id}`" class="text-sm text-indigo-600">Details</router-link>
            <button @click="showQR(e)" class="text-sm border px-3 py-1 rounded">Show QR</button>
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
  name: 'MyEvents',
  components: { Navbar, Footer },
  data() { return { myEvents: [] } },
  async created() {
    const regs = await this.$store.dispatch('fetchMyRegistrations')
    // assume API returns registrations with nested event
    this.myEvents = regs.map(r => apiEventToView(r.event))
  },
  methods: {
    showQR(reg) {
      // If backend returns `qr_token` on registration, display/QR-encode it.
      alert(`QR token: ${reg.qr_token || 'N/A'}`)
    }
  }
}
</script>
