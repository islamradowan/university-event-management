<template>
  <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black opacity-50" @click="close"></div>
    <div class="bg-white rounded-lg shadow-lg z-50 max-w-3xl w-full mx-4 overflow-auto">
      <div class="p-4">
        <slot />
        <div class="flex justify-end mt-4">
          <button @click="close" class="px-3 py-1 border rounded">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Modal',
  props: { visible: { type: Boolean, default: false } },
  methods: {
    close() { this.$emit('update:visible', false); this.$emit('close') }
  }
}
</script>
