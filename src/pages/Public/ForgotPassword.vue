<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <!-- Logo/Brand -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">UniEvents</h1>
        <p class="text-gray-600">Reset your password</p>
      </div>

      <!-- Forgot Password Form -->
      <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        <div v-if="!emailSent">
          <div class="text-center mb-6">
            <div class="mx-auto w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2m-2-2a2 2 0 00-2 2m0 0a2 2 0 01-2 2m2-2V9a2 2 0 00-2-2M9 7a2 2 0 012 2m0 0a2 2 0 012 2m-2-2a2 2 0 00-2 2m0 0a2 2 0 01-2 2m2-2V9a2 2 0 00-2-2"></path>
              </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Forgot your password?</h2>
            <p class="text-sm text-gray-600">Enter your email and we'll send you a reset link</p>
          </div>

          <form @submit.prevent="sendResetLink" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
              <input 
                v-model="email" 
                type="email" 
                required
                :disabled="loading"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:opacity-50"
                placeholder="Enter your email address"
              />
            </div>

            <button 
              type="submit" 
              :disabled="loading || !email"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
            >
              <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ loading ? 'Sending...' : 'Send Reset Link' }}
            </button>
          </form>
        </div>

        <!-- Success State -->
        <div v-else class="text-center">
          <div class="mx-auto w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <h2 class="text-xl font-semibold text-gray-900 mb-2">Check your email</h2>
          <p class="text-sm text-gray-600 mb-6">
            We've sent a password reset link to<br>
            <span class="font-medium">{{ email }}</span>
          </p>
          <button 
            @click="resetForm" 
            class="text-blue-600 hover:text-blue-500 text-sm font-medium"
          >
            Didn't receive the email? Try again
          </button>
        </div>

        <div class="mt-6 text-center">
          <router-link to="/login" class="text-sm text-gray-600 hover:text-gray-500">
            ← Back to sign in
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ForgotPassword',
  data() {
    return {
      email: '',
      loading: false,
      emailSent: false
    }
  },
  methods: {
    async sendResetLink() {
      this.loading = true
      try {
        await this.$http.get('/sanctum/csrf-cookie')
        await this.$http.post('/api/forgot-password', { email: this.email })
        this.emailSent = true
      } catch (err) {
        alert('Failed to send reset link. Please try again.')
      } finally {
        this.loading = false
      }
    },
    resetForm() {
      this.emailSent = false
      this.email = ''
    }
  }
}
</script>