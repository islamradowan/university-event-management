<template>
  <div class="min-h-screen bg-gray-50">
    <AppNavbar />
    
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="mt-2 text-gray-600">System overview and management</p>
          </div>
          <div class="mt-4 sm:mt-0 flex space-x-3">
            <router-link to="/admin/users" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
              </svg>
              Manage Users
            </router-link>
            <router-link to="/admin/reports" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 inline-flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              Reports
            </router-link>
          </div>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Events</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.events }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Users</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.users }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Registrations</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.registrations }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Pending Events</p>
              <p class="text-2xl font-bold text-gray-900">{{ pendingEvents }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Pending Events -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold text-gray-900">Pending Events</h3>
              <router-link to="/admin/events" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                View All
              </router-link>
            </div>
          </div>
          
          <div v-if="pendingEventsList.length === 0" class="p-6 text-center">
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
              <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <p class="text-gray-500 text-sm">No pending events</p>
          </div>
          
          <div v-else class="divide-y divide-gray-200">
            <div v-for="event in pendingEventsList.slice(0, 3)" :key="event.id" class="p-6 hover:bg-gray-50 transition-colors">
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <h4 class="font-medium text-gray-900">{{ event.title }}</h4>
                  <p class="text-sm text-gray-600 mt-1">by {{ event.organizer }}</p>
                </div>
                <div class="flex space-x-2 ml-4">
                  <button @click="approveEvent(event.id)" class="bg-green-100 hover:bg-green-200 text-green-800 px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                    Approve
                  </button>
                  <button @click="rejectEvent(event.id)" class="bg-red-100 hover:bg-red-200 text-red-800 px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                    Reject
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
          </div>
          
          <div class="divide-y divide-gray-200">
            <div v-for="activity in recentActivity" :key="activity.id" class="p-6">
              <div class="flex items-start">
                <div class="flex-shrink-0">
                  <div :class="activity.iconBg" class="w-8 h-8 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4" :class="activity.iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="activity.iconPath"></path>
                    </svg>
                  </div>
                </div>
                <div class="ml-3 flex-1">
                  <p class="text-sm text-gray-900">{{ activity.message }}</p>
                  <p class="text-xs text-gray-500 mt-1">{{ activity.time }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Event Status Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Event Status Distribution</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                <span class="text-sm text-gray-600">Approved</span>
              </div>
              <span class="text-sm font-medium text-gray-900">{{ eventStats.approved }} ({{ eventStats.approvedPercent }}%)</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                <span class="text-sm text-gray-600">Pending</span>
              </div>
              <span class="text-sm font-medium text-gray-900">{{ eventStats.pending }} ({{ eventStats.pendingPercent }}%)</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                <span class="text-sm text-gray-600">Rejected</span>
              </div>
              <span class="text-sm font-medium text-gray-900">{{ eventStats.rejected }} ({{ eventStats.rejectedPercent }}%)</span>
            </div>
          </div>
        </div>

        <!-- User Growth -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">User Breakdown</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-600">Students</span>
              <span class="text-sm font-medium text-gray-900">{{ userStats.students }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-600">Organizers</span>
              <span class="text-sm font-medium text-gray-900">{{ userStats.organizers }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-600">Admins</span>
              <span class="text-sm font-medium text-gray-900">{{ userStats.admins }}</span>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import AppNavbar from '@/components/AppNavbar.vue'

export default {
  name: 'AdminDashboard',
  components: { AppNavbar },
  data() {
    return { 
      stats: { events: 0, users: 0, registrations: 0 },
      pendingEventsList: [],
      allEvents: [],
      allUsers: [],
      loading: true,
      recentActivity: [
        {
          id: 1,
          message: 'New event "AI Workshop" submitted for approval',
          time: '2 hours ago',
          iconBg: 'bg-blue-100',
          iconColor: 'text-blue-600',
          iconPath: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'
        },
        {
          id: 2,
          message: 'User "Alex Johnson" registered as organizer',
          time: '4 hours ago',
          iconBg: 'bg-green-100',
          iconColor: 'text-green-600',
          iconPath: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
        },
        {
          id: 3,
          message: 'Event "Music Concert" completed successfully',
          time: '1 day ago',
          iconBg: 'bg-purple-100',
          iconColor: 'text-purple-600',
          iconPath: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
        }
      ]
    }
  },
  async created() {
    await this.loadDashboardData()
  },
  computed: {
    pendingEvents() {
      return this.pendingEventsList.length
    },
    eventStats() {
      const total = this.allEvents.length || 1
      const approved = this.allEvents.filter(e => e.status === 'approved').length
      const pending = this.allEvents.filter(e => e.status === 'pending').length
      const rejected = this.allEvents.filter(e => e.status === 'rejected').length
      
      return {
        approved,
        pending,
        rejected,
        approvedPercent: Math.round((approved / total) * 100),
        pendingPercent: Math.round((pending / total) * 100),
        rejectedPercent: Math.round((rejected / total) * 100)
      }
    },
    userStats() {
      const students = this.allUsers.filter(u => u.role === 'student').length
      const organizers = this.allUsers.filter(u => u.role === 'organizer').length
      const admins = this.allUsers.filter(u => u.role === 'admin').length
      
      return { students, organizers, admins }
    }
  },
  methods: {
    async approveEvent(id) {
      try {
        await this.$http.put(`/api/events/${id}/approve`)
        alert(`Event ${id} approved`)
        this.pendingEventsList = this.pendingEventsList.filter(e => e.id !== id)

      } catch (err) {
        console.error(err)
        alert('Failed to approve event')
      }
    },
    async rejectEvent(id) {
      try {
        await this.$http.put(`/api/events/${id}/reject`)
        alert(`Event ${id} rejected`)
        this.pendingEventsList = this.pendingEventsList.filter(e => e.id !== id)
      } catch (err) {
        console.error(err)
        alert('Failed to reject event')
      }
    },
    async loadDashboardData() {
      try {
        const [eventsRes, usersRes, statsRes] = await Promise.all([
          this.$http.get('/api/events'),
          this.$http.get('/api/admin/users'),
          this.$http.get('/api/admin/stats')
        ])
        
        this.allEvents = eventsRes.data || []
        this.allUsers = usersRes.data || []
        const pendingEvents = this.allEvents.filter(e => e.status === 'pending')
        
        this.stats = {
          events: this.allEvents.length,
          users: this.allUsers.length,
          registrations: statsRes.data?.registrations || 0
        }
        
        this.pendingEventsList = pendingEvents.map(e => ({
          id: e.id,
          title: e.title,
          organizer: e.organizer?.name || 'Unknown'
        }))
        
      } catch (err) {
        console.error('Failed to load dashboard data:', err)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>