<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">UniEvents</h1>
        <p class="text-gray-600">Reset your password</p>
      </div>

      <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        <form @submit.prevent="resetPassword" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
            <input 
              v-model="password" 
              type="password" 
              required
              :disabled="loading"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all disabled:opacity-50"
              placeholder="Enter new password"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
            <input 
              v-model="confirmPassword" 
              type="password" 
              required
              :disabled="loading"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all disabled:opacity-50"
              placeholder="Confirm new password"
            />
          </div>

          <button 
            type="submit" 
            :disabled="loading || !isValid"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Resetting...' : 'Reset Password' }}
          </button>
        </form>

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
  name: 'ResetPassword',
  data() {
    return {
      password: '',
      confirmPassword: '',
      loading: false,
      token: '',
      email: ''
    }
  },
  computed: {
    isValid() {
      return this.password.length >= 6 && this.password === this.confirmPassword
    }
  },
  created() {
    this.token = this.$route.query.token
    this.email = this.$route.query.email
    
    if (!this.token || !this.email) {
      alert('Invalid reset link')
      this.$router.push('/login')
    }
  },
  methods: {
    async resetPassword() {
      this.loading = true
      try {
        await this.$http.get('/sanctum/csrf-cookie')
        await this.$http.post('/api/reset-password', {
          token: this.token,
          email: this.email,
          password: this.password,
          password_confirmation: this.confirmPassword
        })
        alert('Password reset successfully! Please login with your new password.')
        this.$router.push('/login')
      } catch (err) {
        alert('Failed to reset password. The link may be expired.')
      } finally {
        this.loading = false
      }
    }
  }
}
</script>