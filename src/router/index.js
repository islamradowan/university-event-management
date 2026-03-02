import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'

// Lazy load all components for better performance
const PublicLanding = () => import(/* webpackChunkName: "public" */ '@/pages/Public/PublicLanding.vue')
const PublicLogin = () => import(/* webpackChunkName: "public" */ '@/pages/Public/PublicLogin.vue')
const PublicRegister = () => import(/* webpackChunkName: "public" */ '@/pages/Public/PublicRegister.vue')

const MyEvents = () => import(/* webpackChunkName: "student" */ '@/pages/Student/MyEvents.vue')
const StudentCalendar = () => import(/* webpackChunkName: "student" */ '@/pages/Student/Calendar.vue')
const SearchEvents = () => import(/* webpackChunkName: "student" */ '@/pages/Student/SearchEvents.vue')

const CreateEvent = () => import(/* webpackChunkName: "organizer" */ '@/pages/Organizer/CreateEvent.vue')
const EditEvent = () => import(/* webpackChunkName: "organizer" */ '@/pages/Organizer/EditEvent.vue')
const OrganizerEventDetails = () => import(/* webpackChunkName: "organizer" */ '@/pages/Organizer/EventDetails.vue')
const OrganizerCalendar = () => import(/* webpackChunkName: "organizer" */ '@/pages/Organizer/Calendar.vue')

const AdminManageEvents = () => import(/* webpackChunkName: "admin" */ '@/pages/Admin/AdminManageEvents.vue')
const ManageUsers = () => import(/* webpackChunkName: "admin" */ '@/pages/Admin/ManageUsers.vue')
const SendAnnouncement = () => import(/* webpackChunkName: "admin" */ '@/pages/Admin/SendAnnouncement.vue')
const AdminCalendar = () => import(/* webpackChunkName: "admin" */ '@/pages/Admin/Calendar.vue')
const Reports = () => import(/* webpackChunkName: "admin" */ '@/pages/Admin/Reports.vue')

const UserProfile = () => import(/* webpackChunkName: "profile" */ '@/pages/Profile/UserProfile.vue')

const NotFound = () => import(/* webpackChunkName: "utility" */ '@/pages/Utility/NotFound.vue')
const UtilityMaintenance = () => import(/* webpackChunkName: "utility" */ '@/pages/Utility/UtilityMaintenance.vue')
const CalendarExport = () => import(/* webpackChunkName: "utility" */ '@/pages/Utility/CalendarExport.vue')
const Notifications = () => import(/* webpackChunkName: "utility" */ '@/pages/Utility/Notifications.vue')

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    { path: '/', component: PublicLanding },
    { path: '/login', component: PublicLogin },
    { path: '/register', component: PublicRegister },
    { path: '/forgot-password', component: () => import(/* webpackChunkName: "public" */ '@/pages/Public/ForgotPassword.vue') },
    { path: '/reset-password', component: () => import(/* webpackChunkName: "public" */ '@/pages/Public/ResetPassword.vue') },
    { 
      path: '/profile', 
      component: UserProfile, 
      meta: { requiresAuth: true }
    },

    // Student
    { 
      path: '/student/dashboard', 
      component: () => import(/* webpackChunkName: "student" */ '@/pages/Student/Dashboard.vue'), 
      meta: { requiresAuth: true, role: 'student' }
    },
    { 
      path: '/student/event/:id', 
      component: () => import(/* webpackChunkName: "student" */ '@/pages/Student/EventDetails.vue'), 
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
      component: () => import(/* webpackChunkName: "organizer" */ '@/pages/Organizer/Dashboard.vue'), 
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
    { 
      path: '/organizer/announcements', 
      component: () => import(/* webpackChunkName: "organizer" */ '@/pages/Organizer/SendAnnouncement.vue'), 
      meta: { requiresAuth: true, role: 'organizer' }
    },

    // Admin
    { 
      path: '/admin/dashboard', 
      component: () => import(/* webpackChunkName: "admin" */ '@/pages/Admin/Dashboard.vue'), 
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
      component: () => import(/* webpackChunkName: "student" */ '@/pages/Student/EventDetails.vue'), 
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
      component: () => import(/* webpackChunkName: "profile" */ '@/pages/Profile/NotificationPreferences.vue'), 
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
