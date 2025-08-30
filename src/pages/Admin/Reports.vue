<template>
  <div>
    <AppNavbar />
    <main class="max-w-6xl mx-auto p-6">
      <Breadcrumb :items="[{name: 'Home', to: '/'}, {name: 'Admin', to: '/admin/dashboard'}, {name: 'Reports'}]" />
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Advanced Reports</h1>
      </div>

      <!-- Report Filters -->
      <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h3 class="text-lg font-semibold mb-4">Report Filters</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium mb-2">Start Date</label>
            <input v-model="filters.startDate" type="date" class="w-full border rounded px-3 py-2">
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">End Date</label>
            <input v-model="filters.endDate" type="date" class="w-full border rounded px-3 py-2">
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">Category</label>
            <select v-model="filters.category" class="w-full border rounded px-3 py-2">
              <option value="">All Categories</option>
              <option value="Workshop">Workshop</option>
              <option value="Seminar">Seminar</option>
              <option value="Competition">Competition</option>
              <option value="Festival">Festival</option>
            </select>
          </div>
          <div class="flex items-end">
            <button @click="generateReport" class="w-full bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
              Generate Report
            </button>
          </div>
        </div>
      </div>

      <!-- Report Summary -->
      <div v-if="reportData.summary" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow text-center">
          <div class="text-2xl font-bold text-blue-600">{{ reportData.summary.total_events }}</div>
          <div class="text-sm text-gray-600">Total Events</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow text-center">
          <div class="text-2xl font-bold text-green-600">{{ reportData.summary.total_registrations }}</div>
          <div class="text-sm text-gray-600">Total Registrations</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow text-center">
          <div class="text-2xl font-bold text-purple-600">{{ reportData.summary.total_attendance }}</div>
          <div class="text-sm text-gray-600">Total Attendance</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow text-center">
          <div class="text-2xl font-bold text-yellow-600">{{ reportData.summary.average_rating?.toFixed(1) || 0 }}</div>
          <div class="text-sm text-gray-600">Average Rating</div>
        </div>
      </div>

      <!-- Detailed Report Table -->
      <div v-if="reportData.detailed_events" class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b flex justify-between items-center">
          <h3 class="text-lg font-semibold">Detailed Event Report</h3>
          <div class="flex gap-2">
            <button @click="exportReport('csv')" class="text-sm border px-3 py-1 rounded">
              Export CSV
            </button>
            <button @click="exportReport('json')" class="text-sm border px-3 py-1 rounded">
              Export JSON
            </button>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Organizer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registrations</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attendance</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="event in paginatedEvents" :key="event.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="font-medium text-gray-900">{{ event.title }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                  {{ event.organizer }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                    {{ event.category }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getStatusClass(event.status)" class="px-2 py-1 text-xs font-medium rounded-full">
                    {{ event.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                  {{ event.start_date }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                  {{ event.registrations }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <span class="text-gray-900">{{ event.attendance }}</span>
                    <span class="ml-2 text-sm text-gray-500">({{ event.attendance_rate }}%)</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <span class="text-yellow-400">★</span>
                    <span class="ml-1 text-gray-500">{{ event.average_rating }}</span>
                    <span class="ml-1 text-xs text-gray-400">({{ event.feedback_count }})</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t">
          <Pagination
            :current-page="currentPage"
            :total-pages="totalPages"
            @page-change="currentPage = $event"
          />
        </div>
      </div>
    </main>
    <AppFooter />
  </div>
</template>

<script>
import AppNavbar from '@/components/AppNavbar.vue'
import AppFooter from '@/components/AppFooter.vue'
import Pagination from '@/components/Pagination.vue'
import Breadcrumb from '@/components/Breadcrumb.vue'

export default {
  name: 'Reports',
  components: {
    AppNavbar,
    AppFooter,
    Pagination,
    Breadcrumb
  },
  data() {
    return {
      filters: {
        startDate: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
        endDate: new Date().toISOString().split('T')[0],
        category: ''
      },
      reportData: {},
      currentPage: 1,
      itemsPerPage: 10,
      loading: false
    }
  },
  computed: {
    categoryChartData() {
      if (!this.reportData.charts_data?.events_by_category) return { labels: [], datasets: [] }
      
      const data = this.reportData.charts_data.events_by_category
      return {
        labels: Object.keys(data),
        datasets: [{
          data: Object.values(data),
          backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6']
        }]
      }
    },
    statusChartData() {
      if (!this.reportData.charts_data?.events_by_status) return { labels: [], datasets: [] }
      
      const data = this.reportData.charts_data.events_by_status
      return {
        labels: Object.keys(data),
        datasets: [{
          data: Object.values(data),
          backgroundColor: ['#10B981', '#F59E0B', '#EF4444']
        }]
      }
    },
    paginatedEvents() {
      const events = this.reportData.detailed_events || []
      const start = (this.currentPage - 1) * this.itemsPerPage
      return events.slice(start, start + this.itemsPerPage)
    },
    totalPages() {
      const events = this.reportData.detailed_events || []
      return Math.ceil(events.length / this.itemsPerPage)
    }
  },
  async created() {
    await this.generateReport()
  },
  methods: {
    async generateReport() {
      this.loading = true
      try {
        const params = new URLSearchParams({
          start_date: this.filters.startDate,
          end_date: this.filters.endDate,
          ...(this.filters.category && { category: this.filters.category })
        })
        
        const response = await this.$http.get(`/api/analytics/reports?${params}`)
        this.reportData = response.data
        this.currentPage = 1
      } catch (error) {
        console.error('Failed to generate report:', error)
        alert('Failed to generate report. Please try again.')
      } finally {
        this.loading = false
      }
    },
    async exportReport(format) {
      try {
        const params = new URLSearchParams({
          format,
          type: 'events',
          start_date: this.filters.startDate,
          end_date: this.filters.endDate,
          ...(this.filters.category && { category: this.filters.category })
        })
        
        const response = await this.$http.get(`/api/analytics/export?${params}`, {
          responseType: format === 'csv' ? 'blob' : 'json'
        })
        
        if (format === 'csv') {
          const url = window.URL.createObjectURL(new Blob([response.data]))
          const link = document.createElement('a')
          link.href = url
          link.setAttribute('download', `report_${new Date().toISOString().split('T')[0]}.csv`)
          document.body.appendChild(link)
          link.click()
          link.remove()
        } else {
          const dataStr = JSON.stringify(response.data, null, 2)
          const dataBlob = new Blob([dataStr], { type: 'application/json' })
          const url = window.URL.createObjectURL(dataBlob)
          const link = document.createElement('a')
          link.href = url
          link.setAttribute('download', `report_${new Date().toISOString().split('T')[0]}.json`)
          document.body.appendChild(link)
          link.click()
          link.remove()
        }
      } catch (error) {
        console.error('Export failed:', error)
        alert('Export failed. Please try again.')
      }
    },
    getStatusClass(status) {
      const classes = {
        approved: 'bg-green-100 text-green-800',
        pending: 'bg-yellow-100 text-yellow-800',
        rejected: 'bg-red-100 text-red-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }
  }
}
</script>