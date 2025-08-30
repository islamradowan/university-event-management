<template>
  <div class="bg-white p-6 rounded-lg shadow">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm font-medium text-gray-600">{{ title }}</p>
        <p class="text-3xl font-bold text-gray-900">{{ formattedValue }}</p>
        <p v-if="change !== null" :class="changeClass" class="text-sm font-medium">
          {{ changeText }}
        </p>
      </div>
      <div :class="iconBgClass" class="p-3 rounded-full">
        <component :is="iconComponent" class="w-6 h-6 text-white" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MetricsCard',
  props: {
    title: { type: String, required: true },
    value: { type: [Number, String], required: true },
    change: { type: Number, default: null },
    icon: { type: String, default: 'chart-bar' },
    color: { type: String, default: 'blue' }
  },
  computed: {
    formattedValue() {
      if (typeof this.value === 'number') {
        return this.value.toLocaleString()
      }
      return this.value
    },
    changeClass() {
      if (this.change === null) return ''
      return this.change >= 0 ? 'text-green-600' : 'text-red-600'
    },
    changeText() {
      if (this.change === null) return ''
      const sign = this.change >= 0 ? '+' : ''
      return `${sign}${this.change}% from last month`
    },
    iconBgClass() {
      const colors = {
        blue: 'bg-blue-500',
        green: 'bg-green-500',
        yellow: 'bg-yellow-500',
        red: 'bg-red-500',
        purple: 'bg-purple-500',
        indigo: 'bg-indigo-500'
      }
      return colors[this.color] || colors.blue
    },
    iconComponent() {
      // Simple icon mapping - in real app, use proper icon library
      const icons = {
        'chart-bar': 'div',
        'users': 'div',
        'calendar': 'div',
        'star': 'div'
      }
      return icons[this.icon] || 'div'
    }
  }
}
</script>