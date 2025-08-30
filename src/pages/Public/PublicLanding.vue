<template>
  <div class="min-h-screen bg-white">
    <AppNavbar />

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-purple-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
          <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
            University Events
            <span class="block bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
              Made Simple
            </span>
          </h1>
          <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
            Discover, register, and manage university events seamlessly. Connect with your campus community and never miss an important event again.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <router-link to="/register" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-xl text-lg font-semibold transition-all transform hover:scale-105 shadow-lg">
              Join Now
            </router-link>
            <button @click="scrollToEvents" class="border-2 border-gray-300 hover:border-indigo-600 text-gray-700 hover:text-indigo-600 px-8 py-4 rounded-xl text-lg font-semibold transition-all">
              Browse Events
            </button>
          </div>
        </div>
      </div>
      
      <!-- Floating Elements -->
      <div class="absolute top-20 left-10 w-20 h-20 bg-indigo-200 rounded-full opacity-20 animate-pulse"></div>
      <div class="absolute bottom-20 right-10 w-32 h-32 bg-purple-200 rounded-full opacity-20 animate-pulse delay-1000"></div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">Everything You Need</h2>
          <p class="text-xl text-gray-600">Powerful features to manage your university events</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
          <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 hover:shadow-lg transition-shadow">
            <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Event Discovery</h3>
            <p class="text-gray-600">Find events that match your interests with advanced search and filtering</p>
          </div>

          <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-50 hover:shadow-lg transition-shadow">
            <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Easy Registration</h3>
            <p class="text-gray-600">One-click registration with QR codes for seamless check-in</p>
          </div>

          <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50 hover:shadow-lg transition-shadow">
            <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM16 3h5v5h-5V3zM4 3h6v6H4V3z"></path>
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Real-time Updates</h3>
            <p class="text-gray-600">Get instant notifications about event changes and updates</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Events Section -->
    <section id="events" class="py-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">Upcoming Events</h2>
          <p class="text-xl text-gray-600">Don't miss out on these exciting events</p>
        </div>

        <div v-if="loading" class="text-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
          <p class="text-gray-600 mt-4">Loading events...</p>
        </div>

        <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="event in featuredEvents" :key="event.id" class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow group">
            <div class="h-48 bg-gradient-to-br from-indigo-400 to-purple-500 relative overflow-hidden">
              <img v-if="event.poster" :src="event.poster" :alt="event.title" class="w-full h-full object-cover" />
              <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-10 transition-all"></div>
              <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium">
                {{ event.category }}
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">
                {{ event.title }}
              </h3>
              <p class="text-gray-600 mb-4 line-clamp-2">{{ event.description }}</p>
              <div class="flex items-center justify-between text-sm text-gray-500">
                <span>{{ formatDate(event.start_at) }}</span>
                <span>{{ event.location }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="text-center mt-12">
          <router-link to="/register" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
            View All Events
            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
          </router-link>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
      <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-white mb-6">Ready to Get Started?</h2>
        <p class="text-xl text-indigo-100 mb-8">Join thousands of students already using UniEvents</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <router-link to="/register" class="bg-white text-indigo-600 hover:bg-gray-100 px-8 py-4 rounded-xl text-lg font-semibold transition-colors">
            Create Account
          </router-link>
          <router-link to="/login" class="border-2 border-white text-white hover:bg-white hover:text-indigo-600 px-8 py-4 rounded-xl text-lg font-semibold transition-all">
            Sign In
          </router-link>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h3 class="text-2xl font-bold mb-4">UniEvents</h3>
          <p class="text-gray-400 mb-6">Making university events accessible to everyone</p>
          <div class="flex justify-center space-x-6 text-sm text-gray-400">
            <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
            <a href="#" class="hover:text-white transition-colors">Contact</a>
          </div>
          <p class="text-gray-500 text-sm mt-6">© 2025 UniEvents. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
import AppNavbar from '@/components/AppNavbar.vue'

export default {
  name: 'PublicLanding',
  components: { AppNavbar },
  data() {
    return {
      loading: true,
      featuredEvents: []
    }
  },
  async created() {
    await this.loadEvents()
  },
  methods: {
    async loadEvents() {
      try {
        const response = await this.$http.get('/api/public/events?limit=6')
        this.featuredEvents = response.data.slice(0, 6)
      } catch (error) {
        console.error('Failed to load events:', error)
      } finally {
        this.loading = false
      }
    },
    scrollToEvents() {
      document.getElementById('events').scrollIntoView({ behavior: 'smooth' })
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        year: 'numeric'
      })
    }
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>