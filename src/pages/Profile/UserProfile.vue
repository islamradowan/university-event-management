<template>
  <div>
    <Navbar />
    <main class="max-w-2xl mx-auto p-6">
      <div class="bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Profile Settings</h1>
        
        <div class="space-y-6">
          <div>
            <label class="block text-sm font-medium mb-2">Profile Picture</label>
            <AvatarUpload 
              :current-avatar="user?.avatar ? `http://localhost:8000/storage/${user.avatar}` : null"
              @avatar-updated="onAvatarUpdated"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Name</label>
            <input 
              v-model="form.name" 
              type="text" 
              class="w-full border rounded px-3 py-2"
              placeholder="Full name"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input 
              v-model="form.email" 
              type="email" 
              class="w-full border rounded px-3 py-2"
              placeholder="Email address"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Role</label>
            <input 
              :value="user?.role" 
              type="text" 
              class="w-full border rounded px-3 py-2 bg-gray-50"
              readonly
            />
          </div>

          <div class="flex justify-end space-x-3">
            <button 
              @click="updateProfile"
              class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
            >
              Save Changes
            </button>
          </div>
        </div>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import AvatarUpload from '@/components/AvatarUpload.vue'

export default {
  name: 'UserProfile',
  components: { Navbar, Footer, AvatarUpload },
  data() {
    return {
      form: {
        name: '',
        email: ''
      }
    }
  },
  computed: {
    user() {
      return this.$store.state.user
    }
  },
  created() {
    if (this.user) {
      this.form.name = this.user.name
      this.form.email = this.user.email
    }
  },
  methods: {
    onAvatarUpdated(avatarUrl) {
      // Update user in store
      this.$store.commit('setUser', {
        ...this.user,
        avatar: avatarUrl.replace('http://localhost:8000/storage/', '')
      })
    },
    async updateProfile() {
      try {
        await this.$http.put('/api/user/profile', this.form)
        
        // Update user in store
        this.$store.commit('setUser', {
          ...this.user,
          name: this.form.name,
          email: this.form.email
        })
        
        alert('Profile updated successfully')
      } catch (error) {
        console.error('Update failed:', error)
        alert('Update failed. Please try again.')
      }
    }
  }
}
</script>