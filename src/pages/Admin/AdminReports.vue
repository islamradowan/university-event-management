<template>
  <div>
    <Navbar />
    <main class="max-w-4xl mx-auto p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Export Reports</h1>
        <div class="flex gap-2">
          <router-link to="/admin/dashboard" class="text-sm border px-3 py-1 rounded">Dashboard</router-link>
          <router-link to="/admin/events" class="text-sm border px-3 py-1 rounded">Events</router-link>
          <router-link to="/admin/users" class="text-sm border px-3 py-1 rounded">Manage Users</router-link>
        </div>
      </div>
      
      <div class="bg-white p-4 rounded shadow">
        <form @submit.prevent="exportCSV">
          <label class="text-sm">Date range</label>
          <div class="grid grid-cols-2 gap-2 mt-2">
            <input v-model="from" type="date" class="border rounded px-3 py-2" />
            <input v-model="to" type="date" class="border rounded px-3 py-2" />
          </div>
          <div class="flex justify-end mt-3">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Export CSV</button>
          </div>
        </form>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'

export default {
  name: 'AdminReports',
  components: { Navbar, Footer },
  data() { return { from:'', to:'' } },
  methods: {
    exportCSV() {
      // GET /admin/export-registrations.csv?from=&to=
      alert('Export CSV (demo)')
    }
  }
}
</script>
