<template>
  <header class="bg-white/80 backdrop-blur-lg border-b border-gray-200/50 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <router-link to="/" class="flex items-center space-x-2">
          <div class="w-8 h-8 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">UniEvents</span>
        </router-link>

        <!-- Center Navigation -->
        <nav class="hidden md:flex items-center space-x-1">
          <!-- Public Navigation -->
          <template v-if="!$store.getters.isLoggedIn">
          </template>

          <!-- Student Navigation -->
          <template v-if="$store.getters.userRole === 'student'">
            <router-link to="/student/dashboard" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">Dashboard</router-link>
            <router-link to="/student/calendar" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">Calendar</router-link>
            <router-link to="/student/my-events" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">My Events</router-link>
          </template>

          <!-- Organizer Navigation -->
          <template v-if="$store.getters.userRole === 'organizer'">
            <router-link to="/organizer/dashboard" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">Dashboard</router-link>
            <router-link to="/organizer/calendar" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">Calendar</router-link>
            <router-link to="/organizer/create" class="px-4 py-2 text-sm font-medium bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all">Create Event</router-link>
          </template>

          <!-- Admin Navigation -->
          <template v-if="$store.getters.userRole === 'admin'">
            <router-link to="/admin/dashboard" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">Dashboard</router-link>
            <router-link to="/admin/calendar" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">Calendar</router-link>
            <router-link to="/admin/events" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">Events</router-link>
            <router-link to="/admin/users" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">Users</router-link>
            <router-link to="/admin/announcements" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">Announcements</router-link>
          </template>
        </nav>

        <!-- Mobile Menu Button -->
        <button @click="showMobileMenu = !showMobileMenu" class="md:hidden p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path v-if="!showMobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>

        <!-- Right Side -->
        <div class="flex items-center space-x-4">
          <!-- Search -->
          <SearchBar v-if="showSearch && $store.getters.isLoggedIn" @search="onSearch" />



          <!-- Auth Buttons -->
          <div v-if="!$store.getters.isLoggedIn" class="flex items-center space-x-3">
            <router-link to="/" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors">Home</router-link>
            <router-link to="/register" class="px-4 py-2 text-sm font-medium bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all">Get Started</router-link>
            <router-link to="/login" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors">Sign in</router-link>
          </div>
          
          <!-- User Menu -->
          <div v-else class="relative" ref="userMenu">
            <button @click="showUserMenu = !showUserMenu" class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg transition-all">
              <div class="w-8 h-8 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                {{ userInitials }}
              </div>
              <div class="hidden sm:block text-left">
                <p class="text-sm font-medium text-gray-900">{{ userName }}</p>
                <p class="text-xs text-gray-500 capitalize">{{ $store.getters.userRole }}</p>
              </div>
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            
            <!-- User Dropdown -->
            <div v-if="showUserMenu" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
              <div class="px-4 py-3 border-b border-gray-100">
                <p class="text-sm font-medium text-gray-900">{{ userName }}</p>
                <p class="text-xs text-gray-500">{{ $store.state.user?.email }}</p>
              </div>
              <router-link to="/profile" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profile Settings
              </router-link>
              <button ref="notificationBell" @click.stop="toggleNotifications" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Notifications
                <span v-if="unreadCount > 0" class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full font-medium">{{ unreadCount > 9 ? '9+' : unreadCount }}</span>
              </button>
              <hr class="my-2">
              <button @click="logout" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Sign out
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div v-if="showMobileMenu" class="md:hidden bg-white border-t border-gray-200">
      <div class="px-4 py-3 space-y-2">
        <!-- Public Mobile Navigation -->
        <template v-if="!$store.getters.isLoggedIn">
          <router-link @click="showMobileMenu = false" to="/" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">Home</router-link>
          <router-link @click="showMobileMenu = false" to="/login" class="block px-3 py-2 text-indigo-600 font-medium">Sign in</router-link>
          <router-link @click="showMobileMenu = false" to="/register" class="block px-3 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg text-center">Get Started</router-link>
        </template>

        <!-- Student Mobile Navigation -->
        <template v-if="$store.getters.userRole === 'student'">
          <router-link @click="showMobileMenu = false" to="/student/dashboard" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">Dashboard</router-link>
          <router-link @click="showMobileMenu = false" to="/student/calendar" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">Calendar</router-link>
          <router-link @click="showMobileMenu = false" to="/student/my-events" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">My Events</router-link>
        </template>

        <!-- Organizer Mobile Navigation -->
        <template v-if="$store.getters.userRole === 'organizer'">
          <router-link @click="showMobileMenu = false" to="/organizer/dashboard" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">Dashboard</router-link>
          <router-link @click="showMobileMenu = false" to="/organizer/calendar" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">Calendar</router-link>
          <router-link @click="showMobileMenu = false" to="/organizer/create" class="block px-3 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg text-center">Create Event</router-link>
        </template>

        <!-- Admin Mobile Navigation -->
        <template v-if="$store.getters.userRole === 'admin'">
          <router-link @click="showMobileMenu = false" to="/admin/dashboard" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">Dashboard</router-link>
          <router-link @click="showMobileMenu = false" to="/admin/calendar" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">Calendar</router-link>
          <router-link @click="showMobileMenu = false" to="/admin/events" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">Events</router-link>
          <router-link @click="showMobileMenu = false" to="/admin/users" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">Users</router-link>
          <router-link @click="showMobileMenu = false" to="/admin/announcements" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">Announcements</router-link>
        </template>

        <!-- Mobile User Actions -->
        <template v-if="$store.getters.isLoggedIn">
          <hr class="my-3">
          <router-link @click="showMobileMenu = false" to="/profile" class="block px-3 py-2 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">Profile</router-link>
          <button @click="logout; showMobileMenu = false" class="block w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg">Sign out</button>
        </template>
      </div>
    </div>

    <!-- Notifications Panel -->
    <div v-if="showNotifications" ref="notificationPanel" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-md">
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-2xl transform overflow-hidden rounded-3xl bg-white shadow-2xl transition-all">
          <!-- Header -->
          <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20">
                  <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                  </svg>
                </div>
                <div>
                  <h2 class="text-2xl font-bold text-white">Notifications</h2>
                  <p class="text-indigo-100">{{ notifications.length }} total, {{ unreadCount }} unread</p>
                </div>
              </div>
              <button @click="showNotifications = false" class="rounded-xl bg-white/20 p-2 text-white hover:bg-white/30 transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Notifications List -->
          <div class="max-h-[60vh] overflow-y-auto">
            <div v-for="notification in notifications" :key="notification.id" class="group border-b border-gray-100 p-6 hover:bg-gray-50 cursor-pointer transition-all" @click="openNotification(notification)">
              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                  <div v-if="!notification.read_at" class="h-4 w-4 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 animate-pulse"></div>
                  <div v-else class="h-4 w-4 rounded-full bg-gray-300"></div>
                </div>
                <div class="flex-1 space-y-2">
                  <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">{{ notification.title }}</h3>
                    <span class="text-sm text-gray-500">{{ formatTimeAgo(notification.created_at) }}</span>
                  </div>
                  <p class="text-gray-700 leading-relaxed">{{ notification.message }}</p>
                  <div class="flex items-center justify-between pt-2">
                    <span class="text-xs text-gray-400">{{ formatDate(notification.created_at) }}</span>
                    <button v-if="!notification.read_at" @click.stop="markAsRead(notification)" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                      Mark as read
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Empty State -->
            <div v-if="notifications.length === 0" class="py-16 text-center">
              <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-r from-indigo-100 to-purple-100">
                <svg class="h-10 w-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 mb-2">All caught up!</h3>
              <p class="text-gray-600 mb-6">You don't have any notifications right now</p>
              <button @click="createTestNotification" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create Test Notification
              </button>
            </div>
          </div>

          <!-- Footer -->
          <div class="bg-gray-50 px-8 py-4">
            <div class="flex items-center justify-between">
              <button v-if="unreadCount > 0" @click="markAllAsRead" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                Mark all as read
              </button>
              <div class="text-sm text-gray-500">
                Last updated {{ formatTimeAgo(new Date()) }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Notification Modal -->
    <div v-if="showNotificationModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-md">
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-sm transform overflow-hidden rounded-xl bg-white shadow-2xl transition-all">
          <!-- Header -->
          <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                  <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                  </svg>
                </div>
                <h2 class="text-xl font-bold text-white">Notification</h2>
              </div>
              <button @click="closeNotificationModal" class="rounded-xl bg-white/20 p-2 text-white hover:bg-white/30 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Content -->
          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ selectedNotification?.title }}</h3>
            <p class="text-gray-700 mb-3 text-sm">{{ selectedNotification?.message }}</p>
            <div class="text-xs text-gray-500">
              {{ formatTimeAgo(selectedNotification?.created_at) }}
            </div>
          </div>

          <!-- Footer -->
          <div class="bg-gray-50 px-4 py-2">
            <div class="flex justify-end space-x-2">
              <button @click="closeNotificationModal" class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800 transition-colors">
                Close
              </button>
              <button v-if="!selectedNotification?.read_at" @click="markAsRead(selectedNotification); closeNotificationModal()" class="px-3 py-1 text-sm bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded hover:from-indigo-700 hover:to-purple-700 transition-all">
                Mark as Read
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import SearchBar from './SearchBar.vue'

export default {
  name: 'AppNavbar',
  components: { SearchBar },
  props: {
    showSearch: { type: Boolean, default: true }
  },
  data() {
    return {
      showUserMenu: false,
      showNotifications: false,
      showMobileMenu: false,
      notifications: [],
      unreadCount: 0,
      notificationInterval: null,
      showNotificationModal: false,
      selectedNotification: null
    }
  },
  computed: {
    userName() {
      return this.$store.state.user?.name || 'User'
    },
    userInitials() {
      const name = this.userName
      return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
    }
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside)
    if (this.$store.getters.isLoggedIn) {
      this.loadNotifications()
      this.notificationInterval = setInterval(this.loadNotifications, 60000)
    }
  },
  beforeDestroy() {
    document.removeEventListener('click', this.handleClickOutside)
    if (this.notificationInterval) {
      clearInterval(this.notificationInterval)
    }
  },
  methods: {
    onSearch(q) {
      this.$emit('search', q)
    },
    handleClickOutside(event) {
      const userMenuClicked = this.$refs.userMenu && this.$refs.userMenu.contains(event.target)
      const notificationClicked = this.$refs.notificationPanel && this.$refs.notificationPanel.contains(event.target)
      const bellClicked = this.$refs.notificationBell && this.$refs.notificationBell.contains(event.target)
      
      if (!userMenuClicked && !notificationClicked && !bellClicked) {
        this.showUserMenu = false
        this.showNotifications = false
      }
    },
    async logout() {
      try {
        await this.$store.dispatch('logout')
        this.showUserMenu = false
        this.$router.push('/login')
      } catch (err) {
        console.error(err)
      }
    },
    async loadNotifications() {
      if (!this.$store.getters.isLoggedIn) return
      try {
        const response = await this.$http.get('/api/notifications?limit=10')
        this.notifications = response.data || []
        this.unreadCount = this.notifications.filter(n => !n.read_at).length

      } catch (error) {

        this.notifications = []
        this.unreadCount = 0
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString()
    },
    formatTimeAgo(date) {
      const now = new Date()
      const notificationDate = new Date(date)
      const diffInMinutes = Math.floor((now - notificationDate) / (1000 * 60))
      
      if (diffInMinutes < 1) return 'Just now'
      if (diffInMinutes < 60) return `${diffInMinutes}m ago`
      if (diffInMinutes < 1440) return `${Math.floor(diffInMinutes / 60)}h ago`
      return `${Math.floor(diffInMinutes / 1440)}d ago`
    },
    toggleNotifications() {
      this.showNotifications = !this.showNotifications
      this.showUserMenu = false
      if (this.showNotifications) {
        this.loadNotifications()
      }
    },
    async markAsRead(notification) {
      if (notification.read_at) return
      
      try {
        await this.$http.post(`/api/notifications/${notification.id}/read`)
        notification.read_at = new Date().toISOString()
        this.unreadCount = this.notifications.filter(n => !n.read_at).length
      } catch (error) {
        console.error('Failed to mark notification as read:', error)
      }
    },
    openNotification(notification) {
      this.selectedNotification = notification
      this.showNotificationModal = true
      this.showNotifications = false
      this.markAsRead(notification)
    },
    closeNotificationModal() {
      this.showNotificationModal = false
      this.selectedNotification = null
    },
    truncateMessage(message) {
      return message.length > 50 ? message.substring(0, 50) + '...' : message
    },
    async createTestNotification() {
      try {
        await this.$http.post('/api/notifications/send-all', {
          title: 'Test Notification',
          message: 'This is a test notification to verify the system is working.',
          recipients: 'all'
        })
        this.loadNotifications()
      } catch (error) {
        console.error('Failed to create test notification:', error)
      }
    },
    async markAllAsRead() {
      try {
        for (const notification of this.notifications.filter(n => !n.read_at)) {
          await this.markAsRead(notification)
        }
      } catch (error) {
        console.error('Failed to mark all as read:', error)
      }
    }
  }
}
</script>
