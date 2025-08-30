<template>
  <div>
    <Navbar />
    <main class="max-w-6xl mx-auto p-6">
      <Breadcrumb :items="[
        {name: 'Home', to: '/'}, 
        {name: 'Admin Dashboard', to: '/admin/dashboard'}, 
        {name: 'Manage Users', to: ''}
      ]" />
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">User Management</h1>
      </div>

      <div class="bg-white p-4 rounded shadow">
        <div class="mb-4 flex gap-2">
          <select v-model="filterRole" class="border rounded px-3 py-2">
            <option value="">All Roles</option>
            <option value="student">Students</option>
            <option value="organizer">Organizers</option>
            <option value="admin">Admins</option>
          </select>
          <input v-model="searchQuery" placeholder="Search users..." class="border rounded px-3 py-2 flex-1" />
        </div>

        <div v-if="loading" class="text-center py-8">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
          <p class="text-sm text-gray-600 mt-2">Loading users...</p>
        </div>

        <div v-else>
          <table class="w-full text-left text-sm">
            <thead>
              <tr class="border-b">
                <th class="py-2">Name</th>
                <th class="py-2">Email</th>
                <th class="py-2">Role</th>
                <th class="py-2">Joined</th>
                <th class="py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in paginatedUsers" :key="user.id" class="border-b">
                <td class="py-2">{{ user.name }}</td>
                <td class="py-2">{{ user.email }}</td>
                <td class="py-2">
                  <span class="px-2 py-1 rounded text-xs" :class="getRoleClass(user.role)">
                    {{ user.role }}
                  </span>
                </td>
                <td class="py-2">{{ formatDate(user.created_at) }}</td>
                <td class="py-2">
                  <button @click="editUser(user)" class="px-2 py-1 border rounded text-xs mr-2">Edit</button>
                  <button @click="deleteUser(user)" class="px-2 py-1 bg-red-600 text-white rounded text-xs">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
          
          <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="currentPage = $event" />
        </div>
      </div>

      <!-- Edit User Modal -->
      <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg max-w-md w-full mx-4">
          <h3 class="text-lg font-semibold mb-4">Edit User</h3>
          <form @submit.prevent="updateUser" class="space-y-3">
            <div>
              <label class="block text-sm font-medium mb-1">Name</label>
              <input v-model="editForm.name" type="text" required class="w-full border rounded px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Email</label>
              <input v-model="editForm.email" type="email" required class="w-full border rounded px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Role</label>
              <select v-model="editForm.role" class="w-full border rounded px-3 py-2">
                <option value="student">Student</option>
                <option value="organizer">Organizer</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <div class="flex justify-end space-x-2 pt-4">
              <button type="button" @click="closeEditModal" class="px-4 py-2 border rounded">Cancel</button>
              <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
            </div>
          </form>
        </div>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import Breadcrumb from '@/components/Breadcrumb.vue'
import Pagination from '@/components/Pagination.vue'

export default {
  name: 'ManageUsers',
  components: { Navbar, Footer, Breadcrumb, Pagination },
  data() {
    return { 
      users: [],
      loading: true,
      filterRole: '',
      searchQuery: '',
      currentPage: 1,
      itemsPerPage: 15,
      showEditModal: false,
      editForm: {
        id: null,
        name: '',
        email: '',
        role: ''
      }
    }
  },
  async created() {
    await this.loadUsers()
  },
  computed: {
    filteredUsers() {
      let filtered = this.users
      
      if (this.filterRole) {
        filtered = filtered.filter(user => user.role === this.filterRole)
      }
      
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(user => 
          user.name.toLowerCase().includes(query) || 
          user.email.toLowerCase().includes(query)
        )
      }
      
      return filtered
    },
    paginatedUsers() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      return this.filteredUsers.slice(start, start + this.itemsPerPage)
    },
    totalPages() {
      return Math.ceil(this.filteredUsers.length / this.itemsPerPage)
    }
  },
  methods: {
    async loadUsers() {
      try {
        this.loading = true
        const response = await this.$http.get('/api/admin/users')
        this.users = response.data
      } catch (error) {
        console.error('Failed to load users:', error)
        alert('Failed to load users')
      } finally {
        this.loading = false
      }
    },
    
    editUser(user) {
      this.editForm = {
        id: user.id,
        name: user.name,
        email: user.email,
        role: user.role
      }
      this.showEditModal = true
    },
    
    closeEditModal() {
      this.showEditModal = false
      this.editForm = { id: null, name: '', email: '', role: '' }
    },
    
    async updateUser() {
      try {
        await this.$http.put(`/api/admin/users/${this.editForm.id}`, {
          name: this.editForm.name,
          email: this.editForm.email,
          role: this.editForm.role
        })
        
        // Update local user data
        const userIndex = this.users.findIndex(u => u.id === this.editForm.id)
        if (userIndex !== -1) {
          this.users[userIndex] = { ...this.users[userIndex], ...this.editForm }
        }
        
        this.closeEditModal()
        alert('User updated successfully')
      } catch (error) {
        console.error('Failed to update user:', error)
        alert('Failed to update user')
      }
    },
    
    async deleteUser(user) {
      if (!confirm(`Are you sure you want to delete ${user.name}?`)) return
      
      try {
        await this.$http.delete(`/api/admin/users/${user.id}`)
        this.users = this.users.filter(u => u.id !== user.id)
        alert('User deleted successfully')
      } catch (error) {
        console.error('Failed to delete user:', error)
        alert('Failed to delete user')
      }
    },
    
    getRoleClass(role) {
      const classes = {
        admin: 'bg-red-100 text-red-800',
        organizer: 'bg-blue-100 text-blue-800',
        student: 'bg-green-100 text-green-800'
      }
      return classes[role] || 'bg-gray-100 text-gray-800'
    },
    
    formatDate(dateString) {
      if (!dateString) return ''
      return new Date(dateString).toLocaleDateString()
    }
  },
  watch: {
    filterRole() { this.currentPage = 1 },
    searchQuery() { this.currentPage = 1 }
  }
}
</script>