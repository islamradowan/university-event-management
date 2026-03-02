<template>
  <article class="bg-white rounded shadow overflow-hidden">
    <div class="h-36 bg-slate-100 flex items-center justify-center text-slate-500">
      <span v-if="!posterUrl">{{ event.title }}</span>
      <OptimizedImage 
        v-if="posterUrl" 
        :src="posterUrl" 
        :alt="event.title"
        :width="400"
        :quality="75"
        class="w-full h-36 object-cover" 
      />
    </div>
    <div class="p-4">
      <h3 class="font-semibold">{{ event.title }}</h3>
      <p class="text-sm text-slate-600">{{ formattedDate }} · {{ event.location }}</p>
      <p class="mt-2 text-sm text-slate-700">{{ shortDesc }}</p>
      <div class="mt-3 flex items-center justify-between">
        <div class="text-xs text-slate-500">{{ event.category || 'General' }} · {{ event.status }}</div>
        <div>
          <router-link :to="linkTo" class="text-sm px-2 py-1 border rounded">View</router-link>
        </div>
      </div>
    </div>
  </article>
</template>

<script>
import OptimizedImage from './OptimizedImage.vue'

export default {
  name: 'EventCard',
  components: {
    OptimizedImage
  },
  props: {
    event: { type: Object, required: true },
    role: { type: String, default: 'student' }
  },
  computed: {
    shortDesc() {
      const desc = this.event.description || ''
      return desc.length > 90 ? desc.slice(0, 90) + '...' : desc
    },
    linkTo() {
      const { role } = this
      const { id } = this.event
      if (role === 'organizer') return `/organizer/event/${id}`
      if (role === 'admin') return `/admin/events`
      return `/student/event/${id}`
    },
    posterUrl() {
      return this.event.poster_path ? 
        `${process.env.VUE_APP_API_BASE || 'http://localhost:8000'}/storage/${this.event.poster_path}` : 
        this.event.poster || null
    },
    formattedDate() {
      const date = this.event.date || this.event.start_at
      return date ? new Date(date).toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric' 
      }) : 'TBD'
    }
  }
}
</script>
