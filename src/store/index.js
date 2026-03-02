import Vue from 'vue'
import Vuex from 'vuex'
import http from '../http'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: null,
    eventsCache: new Map(),
    pagination: { page: 1, limit: 20, total: 0 }
  },
  mutations: {
    setUser(state, user) {
      state.user = user
    },
    clearUser(state) {
      state.user = null
    },
    setEventsPage(state, { page, events, total }) {
      state.eventsCache.set(page, events)
      state.pagination = { ...state.pagination, page, total }
    },
    clearEventsCache(state) {
      state.eventsCache.clear()
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
      try {
        await http.post('/api/logout')
      } catch (error) {
        // Ignore logout errors
      }
      commit('clearUser')
      commit('clearEventsCache')
      
      // Reset CSRF and clear cookies
      const { resetCsrf } = await import('../http')
      resetCsrf()
      
      document.cookie.split(";").forEach(function(c) { 
        document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
      });
      window.location.href = '/login'
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

    // Events with pagination and caching
    async fetchEvents({ state, commit }, { page = 1, limit = 20, force = false } = {}) {
      if (!force && state.eventsCache.has(page)) {
        return state.eventsCache.get(page)
      }
      
      const res = await http.get('/api/events', { params: { page, limit } })
      const events = res.data.data || res.data
      const total = res.data.total || events.length
      
      commit('setEventsPage', { page, events, total })
      return events
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
    async createEvent({ commit }, payload) {
      const res = await http.post('/api/events', payload)
      commit('clearEventsCache') // Clear cache after create
      return res.data
    },
    async updateEvent({ commit }, { id, payload }) {
      const res = await http.put(`/api/events/${id}`, payload)
      commit('clearEventsCache') // Clear cache after update
      return res.data
    },
    async deleteEvent({ commit }, id) {
      await http.delete(`/api/events/${id}`)
      commit('clearEventsCache') // Clear cache after delete
    },

    // Registrations
    async registerForEvent(_, eventId) {
      try {
        const res = await http.post(`/api/events/${eventId}/register`)
        return res.data
      } catch (error) {
        console.error('Registration error:', error.response?.data)
        throw error
      }
    },
    async unregisterFromEvent(_, eventId) {
      const res = await http.delete(`/api/events/${eventId}/unregister`)
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
