<template>
  <div v-if="notifications.length > 0" class="fixed top-4 right-4 z-50 space-y-2">
    <div 
      v-for="notification in notifications" 
      :key="notification.id"
      class="bg-white border-l-4 border-blue-500 rounded-lg shadow-lg p-4 max-w-sm animate-slide-in"
    >
      <div class="flex justify-between items-start">
        <div>
          <h4 class="font-semibold text-gray-900">{{ notification.title }}</h4>
          <p class="text-sm text-gray-600 mt-1">{{ notification.message }}</p>
        </div>
        <button @click="removeNotification(notification.id)" class="text-gray-400 hover:text-gray-600">
          ×
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import pusher from '@/plugins/pusher'

export default {
  name: 'RealTimeNotifications',
  data() {
    return {
      notifications: [],
      channel: null
    }
  },
  mounted() {
    this.setupNotifications()
  },
  beforeDestroy() {
    if (this.channel) {
      pusher.unsubscribe(this.channel.name)
    }
  },
  methods: {
    setupNotifications() {
      const user = this.$store.state.user
      if (!user) return

      this.channel = pusher.subscribe(`user.${user.id}`)
      this.channel.bind('NewNotification', (data) => {
        this.notifications.push({
          id: Date.now(),
          title: data.title,
          message: data.message
        })
        
        // Auto remove after 5 seconds
        setTimeout(() => {
          this.removeNotification(this.notifications[0]?.id)
        }, 5000)
      })
    },
    removeNotification(id) {
      this.notifications = this.notifications.filter(n => n.id !== id)
    }
  }
}
</script>

<style scoped>
.animate-slide-in {
  animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}
</style>