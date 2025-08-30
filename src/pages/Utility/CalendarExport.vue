<template>
  <div>
    <Navbar />
    <main class="max-w-4xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-6">Export Calendar</h1>
      
      <div class="bg-white p-6 rounded shadow">
        <h2 class="text-lg font-semibold mb-4">Export Options</h2>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2">Export Format</label>
            <select v-model="exportFormat" class="w-full border rounded px-3 py-2">
              <option value="ics">iCalendar (.ics)</option>
              <option value="csv">CSV (.csv)</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium mb-2">Date Range</label>
            <div class="grid grid-cols-2 gap-2">
              <input v-model="startDate" type="date" class="border rounded px-3 py-2" />
              <input v-model="endDate" type="date" class="border rounded px-3 py-2" />
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-medium mb-2">Event Status</label>
            <div class="space-y-2">
              <label class="flex items-center">
                <input v-model="includeApproved" type="checkbox" class="mr-2" />
                Approved Events
              </label>
              <label class="flex items-center">
                <input v-model="includePending" type="checkbox" class="mr-2" />
                Pending Events
              </label>
            </div>
          </div>
          
          <div class="flex justify-end">
            <button @click="exportCalendar" class="bg-indigo-600 text-white px-4 py-2 rounded">
              Export Calendar
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

export default {
  name: 'CalendarExport',
  components: { Navbar, Footer },
  data() {
    return {
      exportFormat: 'ics',
      startDate: '',
      endDate: '',
      includeApproved: true,
      includePending: false
    }
  },
  created() {
    // Set default date range (next 3 months)
    const now = new Date()
    this.startDate = now.toISOString().split('T')[0]
    const future = new Date()
    future.setMonth(future.getMonth() + 3)
    this.endDate = future.toISOString().split('T')[0]
  },
  methods: {
    async exportCalendar() {
      try {
        const events = await this.$store.dispatch('fetchEvents')
        
        if (this.exportFormat === 'ics') {
          this.exportAsICS(events)
        } else {
          this.exportAsCSV(events)
        }
      } catch (error) {
        console.error('Export failed:', error)
        alert('Export failed')
      }
    },
    exportAsICS(events) {
      let icsContent = 'BEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//UniEvents//Calendar//EN\n'
      
      events.forEach(event => {
        if (this.shouldIncludeEvent(event)) {
          icsContent += 'BEGIN:VEVENT\n'
          icsContent += `UID:${event.id}@unievents.local\n`
          icsContent += `DTSTART:${this.formatDateForICS(event.start_at)}\n`
          icsContent += `DTEND:${this.formatDateForICS(event.end_at)}\n`
          icsContent += `SUMMARY:${event.title}\n`
          icsContent += `DESCRIPTION:${event.description || ''}\n`
          icsContent += `LOCATION:${event.location || ''}\n`
          icsContent += 'END:VEVENT\n'
        }
      })
      
      icsContent += 'END:VCALENDAR'
      
      this.downloadFile(icsContent, 'events.ics', 'text/calendar')
    },
    exportAsCSV(events) {
      const headers = ['Title', 'Start Date', 'End Date', 'Location', 'Category', 'Status', 'Description']
      let csvContent = headers.join(',') + '\n'
      
      events.forEach(event => {
        if (this.shouldIncludeEvent(event)) {
          const row = [
            `"${event.title}"`,
            `"${event.start_at}"`,
            `"${event.end_at}"`,
            `"${event.location || ''}"`,
            `"${event.category || ''}"`,
            `"${event.status}"`,
            `"${event.description || ''}"`
          ]
          csvContent += row.join(',') + '\n'
        }
      })
      
      this.downloadFile(csvContent, 'events.csv', 'text/csv')
    },
    shouldIncludeEvent(event) {
      if (!this.includeApproved && event.status === 'approved') return false
      if (!this.includePending && event.status === 'pending') return false
      
      const eventDate = new Date(event.start_at)
      const start = new Date(this.startDate)
      const end = new Date(this.endDate)
      
      return eventDate >= start && eventDate <= end
    },
    formatDateForICS(dateString) {
      return new Date(dateString).toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z'
    },
    downloadFile(content, filename, mimeType) {
      const blob = new Blob([content], { type: mimeType })
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = filename
      document.body.appendChild(a)
      a.click()
      document.body.removeChild(a)
      window.URL.revokeObjectURL(url)
    }
  }
}
</script>