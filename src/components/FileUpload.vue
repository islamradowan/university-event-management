<template>
  <div class="file-upload">
    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
      <input
        ref="fileInput"
        type="file"
        :accept="accept"
        :multiple="multiple"
        @change="handleFileSelect"
        class="hidden"
      />
      
      <div v-if="!files.length" @click="$refs.fileInput.click()" class="cursor-pointer">
        <div class="text-gray-400 mb-2">
          <svg class="mx-auto h-12 w-12" stroke="currentColor" fill="none" viewBox="0 0 48 48">
            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </div>
        <p class="text-sm text-gray-600">{{ placeholder }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ acceptText }}</p>
      </div>

      <div v-else class="space-y-2">
        <div v-for="(file, index) in files" :key="index" class="flex items-center justify-between bg-gray-50 p-2 rounded">
          <span class="text-sm text-gray-700">{{ file.name }}</span>
          <button @click="removeFile(index)" class="text-red-500 hover:text-red-700">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
          </button>
        </div>
        <button @click="$refs.fileInput.click()" class="text-sm text-indigo-600 hover:text-indigo-800">
          Add more files
        </button>
      </div>
    </div>

    <div v-if="uploading" class="mt-2">
      <div class="bg-gray-200 rounded-full h-2">
        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" :style="{width: uploadProgress + '%'}"></div>
      </div>
      <p class="text-xs text-gray-600 mt-1">Uploading... {{ uploadProgress }}%</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FileUpload',
  props: {
    accept: { type: String, default: 'image/*' },
    multiple: { type: Boolean, default: false },
    placeholder: { type: String, default: 'Click to upload files' },
    acceptText: { type: String, default: 'PNG, JPG, GIF up to 2MB' }
  },
  data() {
    return {
      files: [],
      uploading: false,
      uploadProgress: 0
    }
  },
  methods: {
    handleFileSelect(event) {
      const selectedFiles = Array.from(event.target.files)
      if (this.multiple) {
        this.files = [...this.files, ...selectedFiles]
      } else {
        this.files = selectedFiles
      }
      this.$emit('files-selected', this.files)
    },
    removeFile(index) {
      this.files.splice(index, 1)
      this.$emit('files-selected', this.files)
    },
    async uploadFiles(url, fieldName = 'files') {
      if (!this.files.length) return null

      this.uploading = true
      this.uploadProgress = 0

      const formData = new FormData()
      
      if (this.multiple) {
        this.files.forEach(file => {
          formData.append(`${fieldName}[]`, file)
        })
      } else {
        formData.append(fieldName, this.files[0])
      }

      try {
        const response = await this.$http.post(url, formData, {
          headers: { 'Content-Type': 'multipart/form-data' },
          onUploadProgress: (progressEvent) => {
            this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total)
          }
        })
        
        this.uploading = false
        this.files = []
        this.$refs.fileInput.value = ''
        
        return response.data
      } catch (error) {
        this.uploading = false
        throw error
      }
    }
  }
}
</script>