import Vue from 'vue'
import Vuex from 'vuex'
import http from '../http'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: null,
    eventsCache: null,
    cacheTimestamp: null
  },
  mutations: {
    setUser(state, user) {
      state.user = user
    },
    clearUser(state) {
      state.user = null
    },
    setEventsCache(state, { events, timestamp }) {
      state.eventsCache = events
      state.cacheTimestamp = timestamp
    }
  },
  actions: {
    async fetchUser({ commit }) {
      try {
        const res = await http.get('/api/user')
        commit('setUser', res.data)
      } catch (err) {
        commit('clearUser')
        throw err
      }
    },

    async logout({ commit }) {
      await http.post('/api/logout')
      commit('clearUser')
    },

    // View All Events 
    async fetchPendingEvents() { 
    const res = await http.get('/api/events', { params: { status: 'pending' } })
    return res.data
    },
    async approveEvent(_, id) { 
      const res = await http.put(`/api/events/${id}/approve`)
      return res.data
    },
    async rejectEvent(_, id) {
      const res = await http.put(`/api/events/${id}/reject`)
      return res.data
    },

    async fetchPublicEvents() {
      const res = await http.get('/api/public/events')
      return res.data
    },

    // Events (read) with caching
    async fetchEvents({ state, commit }) {
      const now = Date.now()
      const cacheAge = 5 * 60 * 1000 // 5 minutes
      
      if (state.eventsCache && state.cacheTimestamp && (now - state.cacheTimestamp) < cacheAge) {
        return state.eventsCache
      }
      
      const res = await http.get('/api/events?limit=20')
      commit('setEventsCache', { events: res.data, timestamp: now })
      return res.data
    },
    async fetchEvent(_, eventId) {
      const res = await http.get(`/api/events/${eventId}`)
      return res.data
    },

    // Events (write) — organizer/admin only
    async fetchMyEvents() {
      const res = await http.get('/api/my-events')
      return res.data
    },
    async createEvent(_, payload) {
      const res = await http.post('/api/events', payload)
      return res.data
    },
    async updateEvent(_, { id, payload }) {
      const res = await http.put(`/api/events/${id}`, payload)
      return res.data
    },
    async deleteEvent(_, id) {
      await http.delete(`/api/events/${id}`)
    },

    // Registrations
    async registerForEvent(_, eventId) {
      const res = await http.post(`/api/events/${eventId}/register`)
      return res.data
    },
    async fetchMyRegistrations() {
      const res = await http.get('/api/my-registrations')
      return res.data
    },

    // Attendance (organizer/admin)
    async checkIn(_, registrationId) {
      const res = await http.post(`/api/registrations/${registrationId}/checkin`)
      return res.data
    },

    // Feedback
    async submitFeedback(_, { eventId, rating, comment }) {
      const res = await http.post(`/api/events/${eventId}/feedback`, { rating, comment })
      return res.data
    },
    async fetchFeedbacks(_, eventId) {
      const res = await http.get(`/api/events/${eventId}/feedbacks`)
      return res.data
    },

    // Notifications
    async fetchNotifications() {
      const res = await http.get('/api/notifications')
      return res.data
    },
    async markNotificationRead(_, notificationId) {
      const res = await http.post(`/api/notifications/${notificationId}/read`)
      return res.data
    }
},
  getters: {
    isLoggedIn: state => !!state.user,
    userRole: state => state.user?.role || null
  }
})
