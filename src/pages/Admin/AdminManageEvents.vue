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
        <div v-for="e in pending" :key="e.id" class="flex justify-between items-center py-3 border-b last:border-b-0">
          <div>
            <h3 class="font-semibold">{{ e.title }}</h3>
            <p class="text-sm text-slate-600">Submitted by: {{ e.organizer }}</p>
          </div>
          <div class="space-x-2">
            <button @click="approve(e.id)" class="px-3 py-1 border rounded">Approve</button>
            <button @click="reject(e.id)" class="px-3 py-1 border rounded">Reject</button>
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
    return { 
      pending: [ 
        {id: 1, title: 'New Workshop Proposal', organizer: 'Rashed'},
        {id: 2, title: 'Tech Conference', organizer: 'Alex'}
      ] 
    }
  },
  methods: {
    async approve(id) {
      // PUT /api/events/:id/status {status:'approved'}
      alert(`Approved event ID: ${id}`)
      // In a real app, you would make an API call here
      // await this.$http.put(`/api/events/${id}/status`, { status: 'approved' })
      // Then remove from pending list or refresh data
    },
    async reject(id) {
      alert(`Rejected event ID: ${id}`)
      // await this.$http.put(`/api/events/${id}/status`, { status: 'rejected' })
    }
  }
}
</script>