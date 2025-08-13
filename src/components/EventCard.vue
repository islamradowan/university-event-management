<template>
  <article class="bg-white rounded shadow overflow-hidden">
    <div class="h-36 bg-slate-100 flex items-center justify-center text-slate-500">
      <span v-if="!event.poster">{{ event.title }}</span>
      <img v-if="event.poster" :src="event.poster" alt="poster" class="w-full h-36 object-cover" />
    </div>
    <div class="p-4">
      <h3 class="font-semibold">{{ event.title }}</h3>
      <p class="text-sm text-slate-600">{{ event.date }} · {{ event.time }} · {{ event.location }}</p>
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
export default {
  name: 'EventCard',
  props: {
    event: { type: Object, required: true },
    role: { type: String, default: 'student' }
  },
  computed: {
    shortDesc() {
      return (this.event.description || '').slice(0, 90) + ((this.event.description||'').length>90?'...':'')
    },
    linkTo() {
      if (this.role === 'organizer') return `/organizer/event/${this.event.id}`
      if (this.role === 'admin') return `/admin/events`
      return `/student/event/${this.event.id}`
    }
  }
}
</script>
