<template>
  <div>
    <Navbar />
    <main class="max-w-4xl mx-auto p-6">
      <Breadcrumb :items="[{name: 'Home', to: '/'}, {name: 'Profile', to: '/profile'}, {name: 'Notification Preferences'}]" />
      
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Notification Preferences</h2>
        
        <form @submit.prevent="savePreferences" class="space-y-6">
          <!-- Contact Information -->
          <div class="border-b pb-6">
            <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2">Phone Number</label>
                <input 
                  v-model="form.phone" 
                  type="tel" 
                  placeholder="+1234567890"
                  class="w-full border rounded px-3 py-2"
                />
                <p class="text-xs text-gray-500 mt-1">Required for SMS notifications</p>
              </div>
            </div>
          </div>

          <!-- Notification Types -->
          <div class="space-y-4">
            <h3 class="text-lg font-semibold">Notification Methods</h3>
            
            <div class="space-y-3">
              <label class="flex items-center">
                <input 
                  v-model="form.preferences.in_app" 
                  type="checkbox" 
                  class="mr-3"
                />
                <div>
                  <span class="font-medium">In-App Notifications</span>
                  <p class="text-sm text-gray-600">Show notifications in the application</p>
                </div>
              </label>

              <label class="flex items-center">
                <input 
                  v-model="form.preferences.email" 
                  type="checkbox" 
                  class="mr-3"
                />
                <div>
                  <span class="font-medium">Email Notifications</span>
                  <p class="text-sm text-gray-600">Receive notifications via email</p>
                </div>
              </label>

              <label class="flex items-center">
                <input 
                  v-model="form.preferences.sms" 
                  type="checkbox" 
                  class="mr-3"
                  :disabled="!form.phone"
                />
                <div>
                  <span class="font-medium">SMS Notifications</span>
                  <p class="text-sm text-gray-600">Receive text messages (phone number required)</p>
                </div>
              </label>

              <label class="flex items-center">
                <input 
                  v-model="form.preferences.push" 
                  type="checkbox" 
                  class="mr-3"
                />
                <div>
                  <span class="font-medium">Push Notifications</span>
                  <p class="text-sm text-gray-600">Browser push notifications</p>
                </div>
              </label>
            </div>
          </div>

          <!-- Notification Categories -->
          <div class="border-t pt-6">
            <h3 class="text-lg font-semibold mb-4">Notification Categories</h3>
            <div class="space-y-3">
              <label class="flex items-center">
                <input 
                  v-model="form.categories.events" 
                  type="checkbox" 
                  class="mr-3"
                />
                <span>Event updates and announcements</span>
              </label>

              <label class="flex items-center">
                <input 
                  v-model="form.categories.registrations" 
                  type="checkbox" 
                  class="mr-3"
                />
                <span>Registration confirmations and reminders</span>
              </label>

              <label class="flex items-center">
                <input 
                  v-model="form.categories.admin" 
                  type="checkbox" 
                  class="mr-3"
                />
                <span>Administrative announcements</span>
              </label>
            </div>
          </div>

          <div class="flex justify-end pt-6">
            <button 
              type="submit" 
              :disabled="saving"
              class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 disabled:opacity-50"
            >
              {{ saving ? 'Saving...' : 'Save Preferences' }}
            </button>
          </div>
        </form>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import Breadcrumb from '@/components/Breadcrumb.vue'

export default {
  name: 'NotificationPreferences',
  components: { Navbar, Footer, Breadcrumb },
  data() {
    return {
      saving: false,
      form: {
        phone: '',
        preferences: {
          in_app: true,
          email: true,
          sms: false,
          push: true
        },
        categories: {
          events: true,
          registrations: true,
          admin: true
        }
      }
    }
  },
  async created() {
    await this.loadPreferences()
  },
  methods: {
    async loadPreferences() {
      try {
        const response = await this.$http.get('/api/user/notification-preferences')
        const data = response.data
        
        this.form.phone = data.phone || ''
        this.form.preferences = {
          ...this.form.preferences,
          ...(data.notification_preferences || {})
        }
      } catch (error) {
        console.error('Failed to load preferences:', error)
      }
    },
    async savePreferences() {
      try {
        this.saving = true
        
        await this.$http.put('/api/user/notification-preferences', {
          phone: this.form.phone,
          notification_preferences: {
            ...this.form.preferences,
            ...this.form.categories
          }
        })
        
        alert('Preferences saved successfully!')
      } catch (error) {
        console.error('Failed to save preferences:', error)
        alert('Failed to save preferences')
      } finally {
        this.saving = false
      }
    }
  }
}
</script>