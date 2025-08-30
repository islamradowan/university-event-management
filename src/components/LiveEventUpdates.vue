<template>
  <div v-if="eventUpdates.length > 0" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
    <h4 class="font-semibold text-blue-900 mb-2">Live Updates</h4>
    <div class="space-y-2">
      <div 
        v-for="update in eventUpdates" 
        :key="update.id"
        class="text-sm text-blue-800 bg-white rounded p-2"
      >
        <strong>{{ update.title }}</strong> - {{ update.status }}
        <span class="text-xs text-blue-600 ml-2">{{ formatTime(update.timestamp) }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import pusher from '@/plugins/pusher'

export default {
  name: 'LiveEventUpdates',
  data() {
    return {
      eventUpdates: [],
      channel: null
    }
  },
  mounted() {
    this.setupEventUpdates()
  },
  beforeDestroy() {
    if (this.channel) {
      pusher.unsubscribe('events')
    }
  },
  methods: {
    setupEventUpdates() {
      this.channel = pusher.subscribe('events')
      this.channel.bind('EventUpdated', (data) => {
        this.eventUpdates.unshift({
          id: data.id,
          title: data.title,
          status: data.status,
          organizer: data.organizer,
          timestamp: new Date()
        })
        
        // Keep only last 5 updates
        if (this.eventUpdates.length > 5) {
          this.eventUpdates = this.eventUpdates.slice(0, 5)
        }
      })
    },
    formatTime(date) {
      return date.toLocaleTimeString()
    }
  }
}
</script>