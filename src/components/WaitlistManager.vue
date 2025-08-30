<template>
  <div class="bg-white rounded-lg shadow p-4">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-semibold">Waitlist</h3>
      <span class="text-sm text-gray-600">{{ waitlist.length }} people waiting</span>
    </div>
    
    <div v-if="waitlist.length === 0" class="text-center py-4 text-gray-500">
      No one on waitlist
    </div>
    
    <div v-else class="space-y-2">
      <div 
        v-for="(entry, index) in waitlist" 
        :key="entry.id"
        class="flex justify-between items-center p-3 border rounded"
      >
        <div>
          <span class="font-medium">{{ entry.user.name }}</span>
          <span class="text-sm text-gray-600 ml-2">Position #{{ entry.position }}</span>
        </div>
        <div class="flex gap-2">
          <button 
            v-if="index === 0"
            @click="promoteUser"
            class="bg-green-600 text-white px-3 py-1 rounded text-sm"
          >
            Promote
          </button>
          <span v-else class="text-xs text-gray-400">
            {{ formatDate(entry.joined_at) }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'WaitlistManager',
  props: {
    eventId: { type: [String, Number], required: true }
  },
  data() {
    return {
      waitlist: []
    }
  },
  async created() {
    await this.loadWaitlist()
  },
  methods: {
    async loadWaitlist() {
      try {
        const response = await this.$http.get(`/api/events/${this.eventId}/waitlist`)
        this.waitlist = response.data
      } catch (error) {
        console.error('Failed to load waitlist:', error)
      }
    },
    async promoteUser() {
      try {
        await this.$http.post(`/api/events/${this.eventId}/waitlist/promote`)
        await this.loadWaitlist()
        this.$emit('user-promoted')
        alert('User promoted from waitlist!')
      } catch (error) {
        console.error('Failed to promote user:', error)
        alert('Failed to promote user')
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString()
    }
  }
}
</script>