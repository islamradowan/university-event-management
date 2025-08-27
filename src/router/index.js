import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'

// Public
import PublicLanding from '@/pages/Public/PublicLanding.vue'
import PublicLogin from '@/pages/Public/PublicLogin.vue'
import PublicRegister from '@/pages/Public/PublicRegister.vue'

// Student
import StudentDashboard from '@/pages/Student/Dashboard.vue'
import StudentEventDetails from '@/pages/Student/EventDetails.vue'
import MyEvents from '@/pages/Student/MyEvents.vue'

// Organizer
import OrganizerDashboard from '@/pages/Organizer/Dashboard.vue'
import CreateEvent from '@/pages/Organizer/CreateEvent.vue'
import EditEvent from '@/pages/Organizer/EditEvent.vue'
import OrganizerEventDetails from '@/pages/Organizer/EventDetails.vue'

// Admin
import AdminDashboard from '@/pages/Admin/Dashboard.vue'
import AdminManageEvents from '@/pages/Admin/AdminManageEvents.vue'
import ManageUsers from '@/pages/Admin/ManageUsers.vue'
import AdminReports from '@/pages/Admin/AdminReports.vue'

// Utility
import NotFound from '@/pages/Utility/NotFound.vue'
import UtilityMaintenance from '@/pages/Utility/UtilityMaintenance.vue'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    { path: '/', component: PublicLanding },
    { path: '/login', component: PublicLogin },
    { path: '/register', component: PublicRegister },

    // Student
    { 
      path: '/student/dashboard', 
      component: StudentDashboard, 
      meta: { requiresAuth: true, role: 'student' }
    },
    { 
      path: '/student/event/:id', 
      component: StudentEventDetails, 
      props: true, 
      meta: { requiresAuth: true, role: 'student' }
    },
    { 
      path: '/student/my-events', 
      component: MyEvents, 
      meta: { requiresAuth: true, role: 'student' }
    },

    // Organizer
    { 
      path: '/organizer/dashboard', 
      component: OrganizerDashboard, 
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

    // Admin
    { 
      path: '/admin/dashboard', 
      component: AdminDashboard, 
      meta: { requiresAuth: true, role: 'admin' }
    },
    { 
      path: '/admin/events', 
      component: AdminManageEvents, 
      meta: { requiresAuth: true, role: 'admin' }
    },
    { 
      path: '/admin/users', 
      component: ManageUsers, 
      meta: { requiresAuth: true, role: 'admin' }
    },
    { 
      path: '/admin/reports', 
      component: AdminReports, 
      meta: { requiresAuth: true, role: 'admin' }
    },
    
    { path: '/maintenance', component: UtilityMaintenance },
    { path: '*', component: NotFound }
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

  if (requiresAuth && !store.getters.isLoggedIn) {
    return next('/login')
  }

  if (requiredRole && store.getters.userRole !== requiredRole) {
    return next('/') // unauthorized → redirect home
  }

  next()
})

export default router
