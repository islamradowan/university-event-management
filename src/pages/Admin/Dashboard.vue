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
          <button @click="showNotificationModal = true" class="bg-indigo-600 text-white px-4 py-2 rounded">Send Notification</button>
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
      
      <div v-if="showNotificationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded">
          <h2 class="text-xl">Send Notification</h2>
          <input v-model="notificationTitle" placeholder="Title" class="border rounded px-3 py-2 w-full mt-4"/>
          <textarea v-model="notificationMessage" placeholder="Message" class="border rounded px-3 py-2 w-full mt-4 h-32"></textarea>
          <div class="flex justify-end mt-4">
            <button @click="sendNotification" class="bg-indigo-600 text-white px-4 py-2 rounded">Send</button>
            <button @click="showNotificationModal = false" class="px-4 py-2 border rounded">Cancel</button>
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
  name: 'AdminDashboard',
  components: { Navbar, Footer },
  data() {
    return { 
      stats: { events:120, users:4200, registrations:8910 },
      showNotificationModal: false,
      notificationTitle: '',
      notificationMessage: '',
    }
  },
  methods: {
    async sendNotification() {
      try {
        await this.$http.post('/api/notifications/send-all', {
          title: this.notificationTitle,
          message: this.notificationMessage,
        });
        alert('Notification sent to all users');
        this.showNotificationModal = false;
      } catch (err) {
        console.error(err);
        alert('Failed to send notification');
      }
    }
  }
}
</script>
