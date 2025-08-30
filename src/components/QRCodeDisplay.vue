<template>
  <div class="qr-code-display">
    <div v-if="loading" class="text-center py-8">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
      <p class="text-sm text-gray-600 mt-2">Generating QR Code...</p>
    </div>

    <div v-else-if="qrCode" class="text-center">
      <div class="bg-white p-4 rounded-lg shadow-sm border inline-block">
        <img :src="qrCodeImage" alt="QR Code" class="w-48 h-48 mx-auto" />
      </div>
      <p class="text-xs text-gray-500 mt-2">Show this QR code at the event entrance</p>
      <p class="text-xs text-gray-400 mt-1">Token: {{ qrToken }}</p>
    </div>

    <div v-else-if="error" class="text-center py-8">
      <div class="text-red-500 mb-2">
        <svg class="w-8 h-8 mx-auto" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>
      </div>
      <p class="text-sm text-red-600">{{ error }}</p>
      <button @click="loadQRCode" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800">
        Try Again
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'QRCodeDisplay',
  props: {
    eventId: { type: [String, Number], required: true }
  },
  data() {
    return {
      loading: false,
      qrCode: null,
      qrToken: null,
      error: null
    }
  },
  computed: {
    qrCodeImage() {
      return this.qrCode ? `data:image/svg+xml;base64,${this.qrCode}` : null
    }
  },
  mounted() {
    this.loadQRCode()
  },
  methods: {
    async loadQRCode() {
      this.loading = true
      this.error = null
      
      try {
        const response = await this.$http.get(`/api/events/${this.eventId}/qr-code`)
        this.qrCode = response.data.qr_code
        this.qrToken = response.data.qr_token
      } catch (error) {
        console.error('Failed to load QR code:', error)
        this.error = error.response?.data?.message || 'Failed to generate QR code'
      } finally {
        this.loading = false
      }
    }
  }
}
</script>