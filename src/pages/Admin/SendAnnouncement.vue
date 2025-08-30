<template>
  <div class="min-h-screen bg-gray-50">
    <AppNavbar />
    
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <Breadcrumb :items="[
        {name: 'Home', to: '/'}, 
        {name: 'Dashboard', to: '/admin/dashboard'}, 
        {name: 'Send Announcement'}
      ]" />

      <!-- Header -->
      <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Send Announcement</h1>
            <p class="mt-2 text-gray-600">Send system-wide announcements to users</p>
          </div>
          <div class="mt-4 sm:mt-0 flex space-x-3">
            <router-link to="/admin/dashboard" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
              </svg>
              Dashboard
            </router-link>
            <router-link to="/admin/reports" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 inline-flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              Reports
            </router-link>
          </div>
        </div>
      </div>

      <!-- Announcement Form -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form @submit.prevent="sendAnnouncement" class="divide-y divide-gray-200">
          <!-- Basic Information -->
          <div class="p-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Announcement Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input 
                  v-model="form.title" 
                  type="text"
                  placeholder="Enter announcement title"
                  required 
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select 
                  v-model="form.priority" 
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                >
                  <option value="low">Low Priority</option>
                  <option value="normal">Normal Priority</option>
                  <option value="high">High Priority</option>
                  <option value="urgent">Urgent</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
              <textarea 
                v-model="form.message" 
                placeholder="Write your announcement message..."
                rows="6"
                required 
                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors resize-none"
              ></textarea>
              <p class="mt-2 text-sm text-gray-500">{{ form.message.length }}/500 characters</p>
            </div>
          </div>

          <!-- Recipients -->
          <div class="p-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Recipients</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div 
                v-for="recipient in recipientOptions" 
                :key="recipient.value"
                @click="form.recipients = recipient.value"
                :class="form.recipients === recipient.value ? 'ring-2 ring-indigo-500 border-indigo-500' : 'border-gray-300 hover:border-gray-400'"
                class="relative border rounded-xl p-4 cursor-pointer transition-all duration-200"
              >
                <div class="flex items-center">
                  <div :class="recipient.iconBg" class="w-10 h-10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" :class="recipient.iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="recipient.iconPath"></path>
                    </svg>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ recipient.name }}</p>
                    <p class="text-xs text-gray-500">{{ recipient.description }}</p>
                  </div>
                </div>
                <div v-if="form.recipients === recipient.value" class="absolute top-2 right-2">
                  <div class="w-5 h-5 bg-indigo-600 rounded-full flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Delivery Options -->
          <div class="p-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Delivery Options</h3>
            
            <div class="space-y-4">
              <div class="flex items-center">
                <input 
                  id="email-notification" 
                  v-model="form.sendEmail" 
                  type="checkbox" 
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <label for="email-notification" class="ml-3 text-sm text-gray-700">
                  Send email notification
                </label>
              </div>
              
              <div class="flex items-center">
                <input 
                  id="in-app-notification" 
                  v-model="form.sendInApp" 
                  type="checkbox" 
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <label for="in-app-notification" class="ml-3 text-sm text-gray-700">
                  Send in-app notification
                </label>
              </div>
              
              <div class="flex items-center">
                <input 
                  id="schedule-later" 
                  v-model="form.scheduleForLater" 
                  type="checkbox" 
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <label for="schedule-later" class="ml-3 text-sm text-gray-700">
                  Schedule for later
                </label>
              </div>
              
              <div v-if="form.scheduleForLater" class="ml-7 grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                  <input 
                    v-model="form.scheduledDate" 
                    type="date" 
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                  <input 
                    v-model="form.scheduledTime" 
                    type="time" 
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Preview -->
          <div class="p-8 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Preview</h3>
            
            <div class="bg-white border border-gray-200 rounded-lg p-4">
              <div class="flex items-start">
                <div :class="getPriorityBadgeClass(form.priority)" class="px-2 py-1 rounded-full text-xs font-medium mr-3 mt-1">
                  {{ form.priority.toUpperCase() }}
                </div>
                <div class="flex-1">
                  <h4 class="font-medium text-gray-900">{{ form.title || 'Announcement Title' }}</h4>
                  <p class="text-sm text-gray-600 mt-1">{{ form.message || 'Your announcement message will appear here...' }}</p>
                  <p class="text-xs text-gray-400 mt-2">To: {{ getRecipientName(form.recipients) }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="px-8 py-6 bg-gray-50 flex flex-col sm:flex-row sm:justify-between space-y-3 sm:space-y-0 sm:space-x-3">
            <router-link 
              to="/admin/dashboard" 
              class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg font-medium transition-colors text-center"
            >
              Cancel
            </router-link>
            
            <div class="flex space-x-3">
              <button 
                type="button"
                @click="saveDraft"
                class="flex-1 sm:flex-none px-6 py-3 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg font-medium transition-colors"
              >
                Save Draft
              </button>
              
              <button 
                type="submit"
                :disabled="sending"
                class="flex-1 sm:flex-none bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 disabled:opacity-50 flex items-center justify-center"
              >
                <svg v-if="sending" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ sending ? 'Sending...' : (form.scheduleForLater ? 'Schedule Announcement' : 'Send Now') }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>

<script>
import AppNavbar from '@/components/AppNavbar.vue'
import Breadcrumb from '@/components/Breadcrumb.vue'

export default {
  name: 'SendAnnouncement',
  components: { AppNavbar, Breadcrumb },
  data() {
    return {
      form: {
        title: '',
        message: '',
        priority: 'normal',
        recipients: 'all',
        sendEmail: true,
        sendInApp: true,
        scheduleForLater: false,
        scheduledDate: '',
        scheduledTime: ''
      },
      sending: false,
      recipientOptions: [
        {
          value: 'all',
          name: 'All Users',
          description: 'Students, Organizers & Admins',
          iconBg: 'bg-blue-100',
          iconColor: 'text-blue-600',
          iconPath: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
        },
        {
          value: 'students',
          name: 'Students Only',
          description: 'All registered students',
          iconBg: 'bg-green-100',
          iconColor: 'text-green-600',
          iconPath: 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'
        },
        {
          value: 'organizers',
          name: 'Organizers Only',
          description: 'Event organizers',
          iconBg: 'bg-purple-100',
          iconColor: 'text-purple-600',
          iconPath: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
        }
      ]
    }
  },
  methods: {
    async sendAnnouncement() {
      try {
        this.sending = true
        const response = await this.$http.post('/api/admin/send-announcement', this.form)
        
        const { email_sent, notifications_sent, total_users } = response.data
        let message = `Announcement "${this.form.title}" sent successfully!\n`
        
        if (this.form.sendEmail) {
          message += `📧 Email sent to ${email_sent} users\n`
        }
        if (this.form.sendInApp) {
          message += `🔔 In-app notifications sent to ${notifications_sent} users\n`
        }
        message += `Total recipients: ${total_users}`
        
        alert(message)
        this.$router.push('/admin/dashboard')
      } catch (err) {
        console.error(err)
        alert('Failed to send announcement')
      } finally {
        this.sending = false
      }
    },
    
    async saveDraft() {
      try {
        await this.$http.post('/api/admin/announcements/draft', this.form)
        alert('Announcement saved as draft')
        this.$router.push('/admin/dashboard')
      } catch (err) {
        console.error(err)
        alert('Failed to save draft')
      }
    },
    
    getPriorityBadgeClass(priority) {
      switch(priority) {
        case 'urgent': return 'bg-red-100 text-red-800'
        case 'high': return 'bg-orange-100 text-orange-800'
        case 'normal': return 'bg-blue-100 text-blue-800'
        case 'low': return 'bg-gray-100 text-gray-800'
        default: return 'bg-gray-100 text-gray-800'
      }
    },
    
    getRecipientName(value) {
      const option = this.recipientOptions.find(opt => opt.value === value)
      return option ? option.name : 'All Users'
    }
  }
}
</script>