<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-semibold">Live Attendance</h3>
      <div class="flex items-center space-x-2">
        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
        <span class="text-sm text-gray-600">Live</span>
      </div>
    </div>
    
    <div class="grid grid-cols-2 gap-4 mb-4">
      <div class="text-center">
        <div class="text-2xl font-bold text-blue-600">{{ attendanceStats.total }}</div>
        <div class="text-sm text-gray-600">Registered</div>
      </div>
      <div class="text-center">
        <div class="text-2xl font-bold text-green-600">{{ attendanceStats.checkedIn }}</div>
        <div class="text-sm text-gray-600">Checked In</div>
      </div>
    </div>

    <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
      <div 
        class="bg-green-500 h-2 rounded-full transition-all duration-300" 
        :style="`width: ${attendanceRate}%`"
      ></div>
    </div>
    
    <div v-if="recentCheckIns.length > 0" class="border-t pt-4">
      <h4 class="font-medium mb-2">Recent Check-ins</h4>
      <div class="space-y-2 max-h-32 overflow-y-auto">
        <div 
          v-for="checkIn in recentCheckIns" 
          :key="checkIn.id"
          class="flex justify-between items-center text-sm bg-green-50 rounded p-2"
        >
          <span class="font-medium">{{ checkIn.user_name }}</span>
          <span class="text-gray-500">{{ formatTime(checkIn.timestamp) }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import pusher from '@/plugins/pusher'

export default {
  name: 'LiveAttendanceTracker',
  props: {
    eventId: { type: [String, Number], required: true }
  },
  data() {
    return {
      attendanceStats: {
        total: 0,
        checkedIn: 0
      },
      recentCheckIns: [],
      channel: null
    }
  },
  computed: {
    attendanceRate() {
      return this.attendanceStats.total > 0 
        ? Math.round((this.attendanceStats.checkedIn / this.attendanceStats.total) * 100)
        : 0
    }
  },
  mounted() {
    this.setupAttendanceTracking()
    this.loadInitialData()
  },
  beforeDestroy() {
    if (this.channel) {
      pusher.unsubscribe(`event.${this.eventId}`)
    }
  },
  methods: {
    setupAttendanceTracking() {
      this.channel = pusher.subscribe(`event.${this.eventId}`)
      this.channel.bind('AttendanceUpdated', (data) => {
        this.attendanceStats.checkedIn++
        this.recentCheckIns.unshift({
          id: data.registration_id,
          user_name: data.user_name,
          timestamp: new Date()
        })
        
        // Keep only last 10 check-ins
        if (this.recentCheckIns.length > 10) {
          this.recentCheckIns = this.recentCheckIns.slice(0, 10)
        }
      })
    },
    async loadInitialData() {
      try {
        const response = await this.$http.get(`/api/events/${this.eventId}`)
        const event = response.data
        this.attendanceStats.total = event.registrations?.length || 0
        this.attendanceStats.checkedIn = event.registrations?.filter(r => r.checked_in_at).length || 0
      } catch (error) {
        console.error('Failed to load attendance data:', error)
      }
    },
    formatTime(date) {
      return date.toLocaleTimeString()
    }
  }
}
</script>