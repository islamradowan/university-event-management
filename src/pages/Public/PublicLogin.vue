<template>
  <div>
    <Navbar :showSearch="false" />
    <div class="min-h-screen flex items-center justify-center">
      <div class="max-w-md w-full bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Sign in to UniEvents</h2>
        <form @submit.prevent="login" class="space-y-3">
          <input v-model="email" type="email" placeholder="Email" required class="w-full border rounded px-3 py-2" />
          <input v-model="password" type="password" placeholder="Password" required class="w-full border rounded px-3 py-2" />
          <div class="flex items-center justify-between text-sm">
            <label><input type="checkbox" class="mr-2" /> Remember me</label>
            <router-link to="/register" class="text-indigo-600">Register</router-link>
          </div>
          <button class="w-full bg-indigo-600 text-white px-4 py-2 rounded">Login</button>
        </form>
      </div>
    </div>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'

export default {
  name: 'PublicLogin',
  components: { Navbar, Footer },
  data() { return { email:'', password:'' } },
  methods: {
    async login() {
      try {
        // 1. Hit Laravel Sanctum login endpoint
        await this.$http.post('/api/login', { 
          email: this.email, 
          password: this.password 
        })

        // 2. Get authenticated user data from backend
        await this.$store.dispatch('fetchUser')

        // 3. Redirect based on role
        const role = this.$store.getters.userRole
        if (role === 'admin') {
          this.$router.push('/admin/dashboard')
        } else if (role === 'organizer') {
          this.$router.push('/organizer/dashboard')
        } else if (role === 'student') {
          this.$router.push('/student/dashboard')
        } else {
          // fallback if role not found
          this.$router.push('/')
        }

      } catch (err) {
        console.error(err.response?.data || err.message)
        // optionally show a toast / error message
      }
    }
  }
}
</script>
