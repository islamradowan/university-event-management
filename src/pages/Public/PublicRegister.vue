<template>
  <div>
    <Navbar :showSearch="false" />
    <div class="min-h-screen flex items-center justify-center">
      <div class="max-w-md w-full bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Create an account</h2>
        <form @submit.prevent="register" class="space-y-3">
          <input v-model="name" placeholder="Full name" required class="w-full border rounded px-3 py-2" />
          <input v-model="email" type="email" placeholder="Email" required class="w-full border rounded px-3 py-2" />
          <input v-model="password" type="password" placeholder="Password" required class="w-full border rounded px-3 py-2" />
          <select v-model="role" class="w-full border rounded px-3 py-2">
            <option value="student">Student</option>
            <option value="organizer">Organizer</option>
          </select>
          <button class="w-full bg-indigo-600 text-white px-4 py-2 rounded">Register</button>
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
  name: 'PublicRegister',
  components: { Navbar, Footer },
  data() { return { name:'', email:'', password:'', role:'student' } },
  methods: {
    async register() {
      try {
        const res = await this.$http.post('/api/register', { name:this.name, email:this.email, password:this.password, role:this.role })
        console.log(res.data)
        this.$router.push('/login')
      } catch (err) {
        console.error(err)
        alert('Register failed (demo)')
      }
    }
  }
}
</script>
