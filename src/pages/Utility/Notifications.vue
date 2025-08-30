<template>
  <div>
    <Navbar />
    <main class="max-w-4xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-6">Notifications</h1>
      
      <div v-if="loading" class="text-center py-8">Loading...</div>
      
      <div v-else-if="!notifications.length" class="text-center py-8 text-gray-500">
        No notifications yet
      </div>
      
      <div v-else class="space-y-4">
        <div 
          v-for="notification in notifications" 
          :key="notification.id"
          :class="[
            'bg-white p-4 rounded shadow border-l-4',
            notification.read_at ? 'border-gray-300' : 'border-indigo-500 bg-indigo-50'
          ]"
        >
          <div class="flex justify-between items-start">
            <div class="flex-1">
              <h3 class="font-semibold">{{ notification.title }}</h3>
              <p class="text-gray-700 mt-1">{{ notification.message }}</p>
              <p class="text-sm text-gray-500 mt-2">{{ formatDate(notification.created_at) }}</p>
            </div>
            <button 
              v-if="!notification.read_at"
              @click="markAsRead(notification)"
              class="text-sm text-indigo-600 hover:text-indigo-800"
            >
              Mark as read
            </button>
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
  name: 'Notifications',
  components: { Navbar, Footer },
  data() {
    return {
      notifications: [],
      loading: true
    }
  },
  async created() {
    await this.loadNotifications()
  },
  methods: {
    async loadNotifications() {
      try {
        this.loading = true
        this.notifications = await this.$store.dispatch('fetchNotifications')
      } catch (error) {
        console.error('Failed to load notifications:', error)
      } finally {
        this.loading = false
      }
    },
    async markAsRead(notification) {
      try {
        await this.$store.dispatch('markNotificationRead', notification.id)
        notification.read_at = new Date().toISOString()
      } catch (error) {
        console.error('Failed to mark notification as read:', error)
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleString()
    }
  }
}
</script>