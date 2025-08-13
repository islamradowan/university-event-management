<template>
  <div>
    <Navbar />
    <main class="max-w-6xl mx-auto p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Overview</h1>
        <div class="flex gap-2">
          <router-link to="/admin/events" class="text-sm border px-3 py-1 rounded">Events</router-link>
          <router-link to="/admin/users" class="text-sm border px-3 py-1 rounded">Manage Users</router-link>
          <router-link to="/admin/reports" class="text-sm border px-3 py-1 rounded">Reports</router-link>
        </div>
      </div>
 
      <div class="grid grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded shadow text-center">
          <div class="text-3xl font-bold">{{ stats.events }}</div>
          <div class="text-sm text-slate-600">Events</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
          <div class="text-3xl font-bold">{{ stats.users }}</div>
          <div class="text-sm text-slate-600">Users</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
          <div class="text-3xl font-bold">{{ stats.registrations }}</div>
          <div class="text-sm text-slate-600">Registrations</div>
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
  name: 'AdminDashboard',
  components: { Navbar, Footer },
  data() {
    return { stats: { events:120, users:4200, registrations:8910 } }
  }
}
</script>
