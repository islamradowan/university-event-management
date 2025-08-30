<template>
  <div class="min-h-screen bg-gray-50">
    <AppNavbar />
    
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <Breadcrumb :items="[
        {name: 'Home', to: '/'}, 
        {name: 'Dashboard', to: '/admin/dashboard'}, 
        {name: 'Manage Users'}
      ]" />

      <!-- Header -->
      <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
            <p class="mt-2 text-gray-600">Manage all users in the system</p>
          </div>
          <div class="mt-4 sm:mt-0 flex space-x-3">
            <router-link to="/admin/dashboard" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
              </svg>
              Dashboard
            </router-link>
            <button @click="showCreateModal = true" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 inline-flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Add User
            </button>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Users</p>
              <p class="text-2xl font-bold text-gray-900">{{ users.length }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Students</p>
              <p class="text-2xl font-bold text-gray-900">{{ studentCount }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Organizers</p>
              <p class="text-2xl font-bold text-gray-900">{{ organizerCount }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Admins</p>
              <p class="text-2xl font-bold text-gray-900">{{ adminCount }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
          <div class="flex flex-wrap gap-2">
            <button 
              v-for="role in roleFilters" 
              :key="role.key"
              @click="selectedRole = selectedRole === role.key ? '' : role.key"
              :class="selectedRole === role.key ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
            >
              {{ role.name }}
              <span v-if="role.count !== undefined" class="ml-2 bg-white/20 px-2 py-0.5 rounded-full text-xs">
                {{ role.count }}
              </span>
            </button>
          </div>
          <div class="flex items-center space-x-4">
            <input 
              v-model="searchQuery"
              placeholder="Search users..." 
              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            />
          </div>
        </div>
      </div>

      <!-- Users Table -->
      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
        <p class="text-gray-600 mt-4">Loading users...</p>
      </div>

      <div v-else class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="user in paginatedUsers" :key="user.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                        <span class="text-sm font-medium text-white">
                          {{ user.name.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ user.email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getRoleBadgeClass(user.role)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                    {{ user.role }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(user.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Active
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                  <button 
                    @click="editUser(user)"
                    class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded-lg text-xs font-medium transition-colors"
                  >
                    Edit
                  </button>
                  <button 
                    @click="deleteUser(user)"
                    class="bg-red-100 hover:bg-red-200 text-red-800 px-3 py-1 rounded-lg text-xs font-medium transition-colors"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="px-6 py-4 border-t border-gray-200">
          <Pagination
            :current-page="currentPage"
            :total-pages="totalPages"
            @page-change="currentPage = $event"
          />
        </div>
      </div>
    </main>

    <!-- Create/Edit User Modal -->
    <Modal :visible="showCreateModal" @close="showCreateModal = false">
      <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">{{ editingUser ? 'Edit User' : 'Create New User' }}</h3>
        
        <form @submit.prevent="saveUser" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
            <input 
              v-model="userForm.name" 
              type="text"
              required 
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input 
              v-model="userForm.email" 
              type="email"
              required 
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
            <select 
              v-model="userForm.role" 
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            >
              <option value="student">Student</option>
              <option value="organizer">Organizer</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          
          <div v-if="!editingUser">
            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input 
              v-model="userForm.password" 
              type="password"
              required 
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            />
          </div>
          
          <div class="flex justify-end space-x-3 pt-4">
            <button 
              type="button" 
              @click="showCreateModal = false"
              class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
            >
              Cancel
            </button>
            <button 
              type="submit"
              class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-4 py-2 rounded-lg transition-all duration-200"
            >
              {{ editingUser ? 'Update' : 'Create' }}
            </button>
          </div>
        </form>
      </div>
    </Modal>
  </div>
</template>

<script>
import AppNavbar from '@/components/AppNavbar.vue'
import Breadcrumb from '@/components/Breadcrumb.vue'
import Pagination from '@/components/Pagination.vue'
import Modal from '@/components/Modal.vue'

export default {
  name: 'ManageUsers',
  components: { AppNavbar, Breadcrumb, Pagination, Modal },
  data() {
    return { 
      users: [],
      loading: true,
      selectedRole: '',
      searchQuery: '',
      currentPage: 1,
      itemsPerPage: 10,
      showCreateModal: false,
      editingUser: null,
      userForm: {
        name: '',
        email: '',
        role: 'student',
        password: ''
      }
    }
  },
  async created() {
    await this.loadUsers()
  },
  computed: {
    studentCount() {
      return this.users.filter(u => u.role === 'student').length
    },
    organizerCount() {
      return this.users.filter(u => u.role === 'organizer').length
    },
    adminCount() {
      return this.users.filter(u => u.role === 'admin').length
    },
    roleFilters() {
      return [
        { key: '', name: 'All Users', count: this.users.length },
        { key: 'student', name: 'Students', count: this.studentCount },
        { key: 'organizer', name: 'Organizers', count: this.organizerCount },
        { key: 'admin', name: 'Admins', count: this.adminCount }
      ]
    },
    filteredUsers() {
      let filtered = this.users

      if (this.selectedRole) {
        filtered = filtered.filter(u => u.role === this.selectedRole)
      }

      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(u => 
          u.name.toLowerCase().includes(query) ||
          u.email.toLowerCase().includes(query)
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
    editUser(user) {
      this.editingUser = user
      this.userForm = {
        name: user.name,
        email: user.email,
        role: user.role,
        password: ''
      }
      this.showCreateModal = true
    },
    async deleteUser(user) {
      if (confirm(`Are you sure you want to delete ${user.name}?`)) {
        try {
          await this.$http.delete(`/api/admin/users/${user.id}`)
          this.users = this.users.filter(u => u.id !== user.id)
          alert('User deleted successfully')
        } catch (err) {
          console.error(err)
          alert('Failed to delete user')
        }
      }
    },
    async saveUser() {
      try {
        if (this.editingUser) {
          await this.$http.put(`/api/admin/users/${this.editingUser.id}`, this.userForm)
          Object.assign(this.editingUser, this.userForm)
          alert('User updated successfully')
        } else {
          const response = await this.$http.post('/api/admin/users', this.userForm)
          this.users.push(response.data)
          alert('User created successfully')
        }
        
        this.showCreateModal = false
        this.editingUser = null
        this.userForm = { name: '', email: '', role: 'student', password: '' }
      } catch (err) {
        console.error(err)
        alert('Failed to save user')
      }
    },
    async loadUsers() {
      try {
        const response = await this.$http.get('/api/admin/users')
        this.users = response.data
      } catch (err) {
        console.error('Failed to load users:', err)
        // Fallback to mock data
        this.users = [
          { id: 1, name: 'John Doe', email: 'john@example.com', role: 'student', created_at: '2024-01-15' },
          { id: 2, name: 'Jane Smith', email: 'jane@example.com', role: 'organizer', created_at: '2024-01-10' }
        ]
      } finally {
        this.loading = false
      }
    },
    getRoleBadgeClass(role) {
      switch(role) {
        case 'admin': return 'bg-red-100 text-red-800'
        case 'organizer': return 'bg-purple-100 text-purple-800'
        case 'student': return 'bg-blue-100 text-blue-800'
        default: return 'bg-gray-100 text-gray-800'
      }
    },
    formatDate(date) {
      if (!date) return 'N/A'
      return new Date(date).toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        year: 'numeric'
      })
    }
  }
}
</script>