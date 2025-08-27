<template>
  <div>
    <Navbar />
    <main class="max-w-6xl mx-auto p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Export Reports</h1>
      </div>

      <form @submit.prevent="exportCSV" class="bg-white p-4 rounded shadow">
        <label class="text-sm">Date range</label>
        <div class="grid grid-cols-2 gap-2 mt-2">
          <input v-model="from" type="date" class="border rounded px-3 py-2" />
          <input v-model="to" type="date" class="border rounded px-3 py-2" />
        </div>
        <div class="flex justify-end mt-3">
          <button class="px-4 py-2 bg-indigo-600 text-white rounded">Export CSV</button>
        </div>
      </form>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'

export default {
  name: 'AdminReports',
  components: { Navbar, Footer },
  data() {
    return { from: '', to: '' }
  },
  methods: {
    exportCSV() {
      const url = `/api/admin/export-registrations?from=${this.from}&to=${this.to}`;
      window.location.href = url;
    }
  }
}
</script>
