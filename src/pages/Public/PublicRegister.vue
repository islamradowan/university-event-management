<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-pink-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <!-- Logo/Brand -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">UniEvents</h1>
        <p class="text-gray-600">Create your account</p>
      </div>

      <!-- Register Form -->
      <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        <form @submit.prevent="register" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
            <input 
              v-model="name" 
              type="text" 
              required
              :disabled="loading"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all disabled:opacity-50"
              placeholder="Enter your full name"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input 
              v-model="email" 
              type="email" 
              required
              :disabled="loading"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all disabled:opacity-50"
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
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all disabled:opacity-50 pr-12"
                placeholder="Create a password"
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
            <div class="mt-1 text-xs text-gray-500">
              Password strength: <span :class="passwordStrengthClass">{{ passwordStrength }}</span>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
            <div class="grid grid-cols-2 gap-3">
              <label class="relative">
                <input 
                  v-model="role" 
                  type="radio" 
                  value="student" 
                  class="sr-only"
                />
                <div :class="role === 'student' ? 'ring-2 ring-purple-500 bg-purple-50' : 'bg-gray-50'" class="p-4 rounded-lg border cursor-pointer hover:bg-gray-100 transition-all">
                  <div class="text-sm font-medium">Student</div>
                  <div class="text-xs text-gray-500">Attend events</div>
                </div>
              </label>
              <label class="relative">
                <input 
                  v-model="role" 
                  type="radio" 
                  value="organizer" 
                  class="sr-only"
                />
                <div :class="role === 'organizer' ? 'ring-2 ring-purple-500 bg-purple-50' : 'bg-gray-50'" class="p-4 rounded-lg border cursor-pointer hover:bg-gray-100 transition-all">
                  <div class="text-sm font-medium">Organizer</div>
                  <div class="text-xs text-gray-500">Create events</div>
                </div>
              </label>
            </div>
          </div>

          <div class="flex items-center">
            <input type="checkbox" required class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" />
            <span class="ml-2 text-sm text-gray-600">
              I agree to the <a href="#" class="text-purple-600 hover:text-purple-500">Terms of Service</a> and 
              <a href="#" class="text-purple-600 hover:text-purple-500">Privacy Policy</a>
            </span>
          </div>

          <button 
            type="submit" 
            :disabled="loading || !isFormValid"
            class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-4 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Creating account...' : 'Create account' }}
          </button>
        </form>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Already have an account? 
            <router-link to="/login" class="text-purple-600 hover:text-purple-500 font-medium">Sign in</router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PublicRegister',
  data() { 
    return { 
      name: '', 
      email: '', 
      password: '', 
      role: 'student',
      loading: false,
      showPassword: false
    } 
  },
  computed: {
    passwordStrength() {
      if (!this.password) return 'None'
      if (this.password.length < 6) return 'Weak'
      if (this.password.length < 10) return 'Medium'
      return 'Strong'
    },
    passwordStrengthClass() {
      const classes = {
        None: 'text-gray-400',
        Weak: 'text-red-500',
        Medium: 'text-yellow-500',
        Strong: 'text-green-500'
      }
      return classes[this.passwordStrength]
    },
    isFormValid() {
      return this.name && this.email && this.password.length >= 6 && this.role
    }
  },
  methods: {
    async register() {
      this.loading = true
      try {
        await this.$http.get('/sanctum/csrf-cookie')
        await this.$http.post('/api/register', { 
          name: this.name, 
          email: this.email, 
          password: this.password, 
          role: this.role 
        })
        alert('Registration successful! Please login.')
        this.$router.push('/login')
      } catch (err) {
        alert('Registration failed. Please try again.')
      } finally {
        this.loading = false
      }
    }
  }
}
</script>