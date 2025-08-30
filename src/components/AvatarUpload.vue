<template>
  <div class="avatar-upload">
    <div class="flex items-center space-x-4">
      <div class="relative">
        <img 
          :src="avatarUrl || defaultAvatar" 
          alt="Avatar" 
          class="w-16 h-16 rounded-full object-cover border-2 border-gray-200"
        />
        <button 
          @click="$refs.fileInput.click()"
          class="absolute bottom-0 right-0 bg-indigo-600 text-white rounded-full p-1 hover:bg-indigo-700"
        >
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
          </svg>
        </button>
      </div>
      
      <div>
        <button 
          @click="$refs.fileInput.click()"
          class="text-sm bg-white border border-gray-300 rounded px-3 py-1 hover:bg-gray-50"
        >
          Change Avatar
        </button>
        <p class="text-xs text-gray-500 mt-1">JPG, PNG up to 1MB</p>
      </div>
    </div>

    <input
      ref="fileInput"
      type="file"
      accept="image/*"
      @change="handleFileSelect"
      class="hidden"
    />

    <div v-if="uploading" class="mt-2">
      <div class="bg-gray-200 rounded-full h-1">
        <div class="bg-indigo-600 h-1 rounded-full transition-all duration-300" :style="{width: uploadProgress + '%'}"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AvatarUpload',
  props: {
    currentAvatar: { type: String, default: null }
  },
  data() {
    return {
      uploading: false,
      uploadProgress: 0,
      avatarUrl: this.currentAvatar
    }
  },
  computed: {
    defaultAvatar() {
      return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMzIiIGN5PSIzMiIgcj0iMzIiIGZpbGw9IiNGM0Y0RjYiLz4KPHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4PSIxNiIgeT0iMTYiPgo8cGF0aCBkPSJNMTYgMTZDMTguMjA5MSAxNiAyMCAxNC4yMDkxIDIwIDEyQzIwIDkuNzkwODYgMTguMjA5MSA4IDE2IDhDMTMuNzkwOSA4IDEyIDkuNzkwODYgMTIgMTJDMTIgMTQuMjA5MSAxMy43OTA5IDE2IDE2IDE2WiIgZmlsbD0iIzlDQTNBRiIvPgo8cGF0aCBkPSJNMTYgMThDMTIuNjg2MyAxOCAxMCAyMC42ODYzIDEwIDI0VjI2SDIyVjI0QzIyIDIwLjY4NjMgMTkuMzEzNyAxOCAxNiAxOFoiIGZpbGw9IiM5Q0EzQUYiLz4KPC9zdmc+Cjwvc3ZnPgo='
    }
  },
  watch: {
    currentAvatar(newVal) {
      this.avatarUrl = newVal
    }
  },
  methods: {
    async handleFileSelect(event) {
      const file = event.target.files[0]
      if (!file) return

      // Validate file
      if (file.size > 1024 * 1024) {
        alert('File size must be less than 1MB')
        return
      }

      if (!file.type.startsWith('image/')) {
        alert('Please select an image file')
        return
      }

      try {
        this.uploading = true
        this.uploadProgress = 0

        const formData = new FormData()
        formData.append('avatar', file)

        const response = await this.$http.post('/api/upload/avatar', formData, {
          headers: { 'Content-Type': 'multipart/form-data' },
          onUploadProgress: (progressEvent) => {
            this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total)
          }
        })

        this.avatarUrl = response.data.avatar_url
        this.$emit('avatar-updated', response.data.avatar_url)
        
      } catch (error) {
        console.error('Upload failed:', error)
        alert('Upload failed. Please try again.')
      } finally {
        this.uploading = false
        event.target.value = ''
      }
    }
  }
}
</script>