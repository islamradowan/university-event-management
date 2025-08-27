<template>
  <div>
    <Navbar />
    <main class="max-w-6xl mx-auto p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Pending Events</h1>
        <div class="flex gap-2">
          <router-link to="/admin/dashboard" class="text-sm border px-3 py-1 rounded">Dashboard</router-link>
          <router-link to="/admin/users" class="text-sm border px-3 py-1 rounded">Manage Users</router-link>
          <router-link to="/admin/reports" class="text-sm border px-3 py-1 rounded">Reports</router-link>
        </div>
      </div>
      
      <div class="bg-white p-4 rounded shadow">
        <div v-if="loading" class="py-6 text-center text-slate-500">Loading…</div>
        <div v-else-if="!pending.length" class="py-6 text-center text-slate-500">No pending events 🎉</div>
        <div v-else>
          <div v-for="e in pending" :key="e.id" class="flex justify-between items-center py-3 border-b last:border-b-0">
            <div>
              <h3 class="font-semibold">{{ e.title }}</h3>
              <p class="text-sm text-slate-600">
                Submitted by: {{ e.organizer?.name || '-' }}
                · {{ new Date(e.start_at).toLocaleString() }}
                · {{ e.location || '—' }}
            </p>
            </div>
            <div class="space-x-2">
              <button @click="approve(e)" class="px-3 py-1 border rounded">Approve</button>
              <button @click="reject(e)" class="px-3 py-1 border rounded">Reject</button>
            </div>
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

export default {
  name: 'AdminManageEvents',
  components: { Navbar, Footer },
  data() {
    return { pending: [], loading: true }
  },
  async created() {
    await this.load()
  },
  methods: {
    async load() {
      try {
        this.loading = true
        this.pending = await this.$store.dispatch('fetchPendingEvents')
      } finally {
        this.loading = false
      }
    },
    async approve(e) {
      try {
        await this.$store.dispatch('approveEvent', e.id)
        this.pending = this.pending.filter(x => x.id !== e.id)
      } catch (err) {
        console.error(err); alert('Approval failed')
      }
    },
    async reject(e) {
      try {
        await this.$store.dispatch('rejectEvent', e.id)
        this.pending = this.pending.filter(x => x.id !== e.id)
      } catch (err) {
        console.error(err); alert('Rejection failed')
      }
    }
  }
}
</script>