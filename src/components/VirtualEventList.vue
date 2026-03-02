<template>
  <div class="virtual-list-container">
    <RecycleScroller
      class="scroller"
      :items="events"
      :item-size="320"
      key-field="id"
      v-slot="{ item }"
    >
      <div class="event-item p-4">
        <EventCard :event="item" :role="role" />
      </div>
    </RecycleScroller>
  </div>
</template>

<script>
import { RecycleScroller } from 'vue-virtual-scroller'
import EventCard from './EventCard.vue'

export default {
  name: 'VirtualEventList',
  components: {
    RecycleScroller,
    EventCard
  },
  props: {
    events: {
      type: Array,
      default: () => []
    },
    role: {
      type: String,
      default: 'student'
    }
  }
}
</script>

<style scoped>
.virtual-list-container {
  height: 600px;
}

.scroller {
  height: 100%;
}

.event-item {
  margin-bottom: 16px;
}
</style>