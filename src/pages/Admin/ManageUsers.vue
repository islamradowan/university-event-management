<template>
  <div>
    <Navbar />
    <main class="max-w-6xl mx-auto p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">User Management</h1>
        <div class="flex gap-2">
          <router-link to="/admin/dashboard" class="text-sm border px-3 py-1 rounded">Dashboard</router-link>
          <router-link to="/admin/events" class="text-sm border px-3 py-1 rounded">Events</router-link>
          <router-link to="/admin/reports" class="text-sm border px-3 py-1 rounded">Reports</router-link>
        </div>
      </div>

      <div class="bg-white p-4 rounded shadow">
        <table class="w-full text-left text-sm">
          <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead>
          <tbody>
            <tr v-for="u in users" :key="u.email">
              <td>{{ u.name }}</td><td>{{ u.email }}</td><td>{{ u.role }}</td>
              <td><button class="px-2 py-1 border rounded">Edit</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'

export default {
  name: 'ManageUsers',
  components: { Navbar, Footer },
  data() {
    return { users: [ {name:'Rashed', email:'rashed@example.com', role:'Organizer'} ] }
  }
}
</script>
