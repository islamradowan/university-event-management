import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'

// Public
import PublicLanding from '@/pages/Public/PublicLanding.vue'
import PublicLogin from '@/pages/Public/PublicLogin.vue'
import PublicRegister from '@/pages/Public/PublicRegister.vue'

// Student
import MyEvents from '@/pages/Student/MyEvents.vue'
import StudentCalendar from '@/pages/Student/Calendar.vue'
import SearchEvents from '@/pages/Student/SearchEvents.vue'

// Organizer
import CreateEvent from '@/pages/Organizer/CreateEvent.vue'
import EditEvent from '@/pages/Organizer/EditEvent.vue'
import OrganizerEventDetails from '@/pages/Organizer/EventDetails.vue'
import OrganizerCalendar from '@/pages/Organizer/Calendar.vue'

// Admin
import AdminManageEvents from '@/pages/Admin/AdminManageEvents.vue'
import ManageUsers from '@/pages/Admin/ManageUsers.vue'
import SendAnnouncement from '@/pages/Admin/SendAnnouncement.vue'
import AdminCalendar from '@/pages/Admin/Calendar.vue'
import Reports from '@/pages/Admin/Reports.vue'

// Profile
import UserProfile from '@/pages/Profile/UserProfile.vue'

// Utility
import NotFound from '@/pages/Utility/NotFound.vue'
import UtilityMaintenance from '@/pages/Utility/UtilityMaintenance.vue'
import CalendarExport from '@/pages/Utility/CalendarExport.vue'
import Notifications from '@/pages/Utility/Notifications.vue'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    { path: '/', component: PublicLanding },
    { path: '/login', component: PublicLogin },
    { path: '/register', component: PublicRegister },
    { path: '/forgot-password', component: () => import('@/pages/Public/ForgotPassword.vue') },
    { path: '/reset-password', component: () => import('@/pages/Public/ResetPassword.vue') },
    { 
      path: '/profile', 
      component: UserProfile, 
      meta: { requiresAuth: true }
    },

    // Student
    { 
      path: '/student/dashboard', 
      component: () => import('@/pages/Student/Dashboard.vue'), 
      meta: { requiresAuth: true, role: 'student' }
    },
    { 
      path: '/student/event/:id', 
      component: () => import('@/pages/Student/EventDetails.vue'), 
      props: true, 
      meta: { requiresAuth: true, role: 'student' }
    },
    { 
      path: '/student/my-events', 
      component: MyEvents, 
      meta: { requiresAuth: true, role: 'student' }
    },
    { 
      path: '/student/calendar', 
      component: StudentCalendar, 
      meta: { requiresAuth: true, role: 'student' }
    },
    { 
      path: '/student/search', 
      component: SearchEvents, 
      meta: { requiresAuth: true, role: 'student' }
    },

    // Organizer
    { 
      path: '/organizer/dashboard', 
      component: () => import('@/pages/Organizer/Dashboard.vue'), 
      meta: { requiresAuth: true, role: 'organizer' }
    },
    { 
      path: '/organizer/create', 
      component: CreateEvent, 
      meta: { requiresAuth: true, role: 'organizer' }
    },
    { 
      path: '/organizer/edit/:id', 
      component: EditEvent, 
      props: true, 
      meta: { requiresAuth: true, role: 'organizer' }
    },
    { 
      path: '/organizer/event/:id', 
      component: OrganizerEventDetails, 
      props: true, 
      meta: { requiresAuth: true, role: 'organizer' }
    },
    { 
      path: '/organizer/calendar', 
      component: OrganizerCalendar, 
      meta: { requiresAuth: true, role: 'organizer' }
    },

    // Admin
    { 
      path: '/admin/dashboard', 
      component: () => import('@/pages/Admin/Dashboard.vue'), 
      meta: { requiresAuth: true, role: 'admin' }
    },
    { 
      path: '/admin/events', 
      component: AdminManageEvents, 
      meta: { requiresAuth: true, role: 'admin' }
    },
    { 
      path: '/admin/reports', 
      component: Reports, 
      meta: { requiresAuth: true, role: 'admin' }
    },
    { 
      path: '/admin/users', 
      component: ManageUsers, 
      meta: { requiresAuth: true, role: 'admin' }
    },
    { 
      path: '/admin/announcements', 
      component: SendAnnouncement, 
      meta: { requiresAuth: true, role: 'admin' }
    },
    { 
      path: '/admin/calendar', 
      component: AdminCalendar, 
      meta: { requiresAuth: true, role: 'admin' }
    },
    { 
      path: '/admin/event/:id', 
      component: () => import('@/pages/Student/EventDetails.vue'), 
      props: true, 
      meta: { requiresAuth: true, role: 'admin' }
    },
    
    { path: '/maintenance', component: UtilityMaintenance },
    { 
      path: '/calendar/export', 
      component: CalendarExport, 
      meta: { requiresAuth: true }
    },
    { 
      path: '/notifications', 
      component: Notifications, 
      meta: { requiresAuth: true }
    },
    { 
      path: '/profile/notifications', 
      component: () => import('@/pages/Profile/NotificationPreferences.vue'), 
      meta: { requiresAuth: true }
    },
    { path: '*', name: 'NotFound', component: NotFound }
  ]
})

// Navigation guard
router.beforeEach(async (to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const requiredRole = to.meta.role

  // If no user in store, try fetching from backend
  if (!store.state.user) {
    await store.dispatch('fetchUser').catch(() => {})
  }

  // Redirect logged-in users from home page to their dashboard
  if (to.path === '/' && store.getters.isLoggedIn) {
    const role = store.getters.userRole
    if (role === 'admin') {
      return next('/admin/dashboard')
    } else if (role === 'organizer') {
      return next('/organizer/dashboard')
    } else if (role === 'student') {
      return next('/student/dashboard')
    }
  }

  if (requiresAuth && !store.getters.isLoggedIn) {
    return next('/login')
  }

  if (requiredRole && store.getters.userRole !== requiredRole) {
    return next('/') // unauthorized → redirect home
  }

  next()
})

export default router
