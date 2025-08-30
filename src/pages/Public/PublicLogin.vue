<template>
  <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-cyan-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <!-- Logo/Brand -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">UniEvents</h1>
        <p class="text-gray-600">Sign in to your account</p>
      </div>

      <!-- Login Form -->
      <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        <form @submit.prevent="login" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input 
              v-model="email" 
              type="email" 
              required
              :disabled="loading"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:opacity-50"
              placeholder="Enter your email"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <div class="relative">
              <input 
                v-model="password" 
                :type="showPassword ? 'text' : 'password'"
                required
                :disabled="loading"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:opacity-50 pr-12"
                placeholder="Enter your password"
              />
              <button 
                type="button" 
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
              >
                <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                </svg>
              </button>
            </div>
          </div>

          <div class="flex items-center justify-between">
            <label class="flex items-center">
              <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
              <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
            <router-link to="/forgot-password" class="text-sm text-indigo-600 hover:text-indigo-500">Forgot password?</router-link>
          </div>

          <button 
            type="submit" 
            :disabled="loading"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Signing in...' : 'Sign in' }}
          </button>
        </form>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Don't have an account? 
            <router-link to="/register" class="text-indigo-600 hover:text-indigo-500 font-medium">Sign up</router-link>
          </p>
        </div>
      </div>

      <!-- Quick Demo Links -->
      <div class="mt-6 text-center">
        <p class="text-xs text-gray-500 mb-2">Quick Demo Access:</p>
        <div class="flex justify-center space-x-2 text-xs">
          <button @click="quickLogin('admin')" class="px-3 py-1 bg-red-100 text-red-700 rounded">Admin</button>
          <button @click="quickLogin('organizer')" class="px-3 py-1 bg-blue-100 text-blue-700 rounded">Organizer</button>
          <button @click="quickLogin('student')" class="px-3 py-1 bg-green-100 text-green-700 rounded">Student</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PublicLogin',
  data() { 
    return { 
      email: '', 
      password: '', 
      loading: false,
      showPassword: false
    } 
  },
  methods: {
    async login() {
      this.loading = true
      try {
        await this.$http.get('/sanctum/csrf-cookie')
        await this.$http.post('/api/login', { 
          email: this.email, 
          password: this.password 
        })
        await this.$store.dispatch('fetchUser')
        
        const role = this.$store.getters.userRole
        const routes = {
          admin: '/admin/dashboard',
          organizer: '/organizer/dashboard', 
          student: '/student/dashboard'
        }
        this.$router.push(routes[role] || '/')
      } catch (err) {
        alert('Login failed. Please check your credentials.')
      } finally {
        this.loading = false
      }
    },
    quickLogin(role) {
      const demos = {
        admin: { email: 'admin@demo.com', password: 'password' },
        organizer: { email: 'organizer@demo.com', password: 'password' },
        student: { email: 'student@demo.com', password: 'password' }
      }
      const demo = demos[role]
      if (demo) {
        this.email = demo.email
        this.password = demo.password
      }
    }
  }
}
</script>