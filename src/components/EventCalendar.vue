<template>
  <div class="calendar-container">
    <FullCalendar
      :options="calendarOptions"
      ref="fullCalendar"
    />
  </div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'

export default {
  name: 'EventCalendar',
  components: {
    FullCalendar
  },
  props: {
    events: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      calendarOptions: {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'dayGridMonth',
        editable: false,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        weekends: true,
        events: [],
        eventClick: this.handleEventClick,
        dateClick: this.handleDateClick,
        height: 'auto'
      }
    }
  },
  watch: {
    events: {
      handler(newEvents) {
        this.calendarOptions.events = this.formatEventsForCalendar(newEvents)
      },
      immediate: true
    }
  },
  methods: {
    formatEventsForCalendar(events) {
      return events.map(event => ({
        id: event.id,
        title: event.title,
        start: event.start_at,
        end: event.end_at,
        backgroundColor: this.getEventColor(event.status),
        borderColor: this.getEventColor(event.status),
        extendedProps: {
          description: event.description,
          location: event.location,
          category: event.category,
          status: event.status,
          organizer: event.organizer
        }
      }))
    },
    getEventColor(status) {
      switch (status) {
        case 'approved': return '#10b981'
        case 'pending': return '#f59e0b'
        case 'rejected': return '#ef4444'
        default: return '#6b7280'
      }
    },
    handleEventClick(clickInfo) {
      const event = clickInfo.event
      this.$emit('event-click', {
        id: event.id,
        title: event.title,
        start: event.start,
        end: event.end,
        ...event.extendedProps
      })
    },
    handleDateClick(dateClickInfo) {
      this.$emit('date-click', dateClickInfo.date)
    }
  }
}
</script>

<style>
.calendar-container {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.fc-toolbar-title {
  font-size: 1.5rem !important;
  font-weight: 600 !important;
}

.fc-button-primary {
  background-color: #4f46e5 !important;
  border-color: #4f46e5 !important;
}

.fc-button-primary:hover {
  background-color: #4338ca !important;
  border-color: #4338ca !important;
}

.fc-event {
  cursor: pointer;
}

.fc-event:hover {
  opacity: 0.8;
}
</style>