<template>
  <div class="qr-scanner">
    <div class="bg-white p-6 rounded-lg shadow">
      <h3 class="text-lg font-semibold mb-4">QR Code Scanner</h3>
      
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-2">Scan QR Code</label>
          <input 
            v-model="qrToken" 
            type="text" 
            placeholder="Enter QR token or scan code"
            class="w-full border rounded px-3 py-2"
            @keyup.enter="scanQR"
          />
        </div>

        <div class="flex gap-2">
          <button 
            @click="scanQR" 
            :disabled="!qrToken || scanning"
            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 disabled:opacity-50"
          >
            {{ scanning ? 'Scanning...' : 'Check In' }}
          </button>
          <button 
            @click="clearResult" 
            class="px-4 py-2 border rounded hover:bg-gray-50"
          >
            Clear
          </button>
        </div>

        <div v-if="result" class="mt-4 p-4 rounded" :class="resultClass">
          <div class="flex items-center">
            <svg v-if="result.success" class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <svg v-else class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ result.message }}</span>
          </div>
          
          <div v-if="result.success && result.registration" class="mt-2 text-sm">
            <p><strong>Student:</strong> {{ result.registration.user?.name }}</p>
            <p><strong>Event:</strong> {{ result.registration.event?.title }}</p>
            <p><strong>Check-in Time:</strong> {{ formatDate(result.registration.checked_in_at) }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'QRScanner',
  data() {
    return {
      qrToken: '',
      scanning: false,
      result: null
    }
  },
  computed: {
    resultClass() {
      if (!this.result) return ''
      return this.result.success 
        ? 'bg-green-50 border border-green-200 text-green-800'
        : 'bg-red-50 border border-red-200 text-red-800'
    }
  },
  methods: {
    async scanQR() {
      if (!this.qrToken.trim()) return

      this.scanning = true
      this.result = null

      try {
        const response = await this.$http.post('/api/qr/scan', {
          qr_token: this.qrToken.trim()
        })

        this.result = {
          success: true,
          message: response.data.message,
          registration: response.data.registration
        }

        this.qrToken = ''
        this.$emit('check-in-success', response.data.registration)

      } catch (error) {
        console.error('Scan failed:', error)
        this.result = {
          success: false,
          message: error.response?.data?.message || 'Scan failed'
        }
      } finally {
        this.scanning = false
      }
    },

    clearResult() {
      this.result = null
      this.qrToken = ''
    },

    formatDate(dateString) {
      if (!dateString) return ''
      return new Date(dateString).toLocaleString()
    }
  }
}
</script>