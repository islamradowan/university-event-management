import Vue from 'vue'
import Router from 'vue-router'

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

export default new Router({
  mode: 'history',
  routes: [
    { path: '/', component: PublicLanding },
    { path: '/login', component: PublicLogin },
    { path: '/register', component: PublicRegister },

    // Student
    { path: '/student/dashboard', component: StudentDashboard },
    { path: '/student/event/:id', component: StudentEventDetails, props: true },
    { path: '/student/my-events', component: MyEvents },

    // Organizer
    { path: '/organizer/dashboard', component: OrganizerDashboard },
    { path: '/organizer/create', component: CreateEvent },
    { path: '/organizer/edit/:id', component: EditEvent, props: true },
    { path: '/organizer/event/:id', component: OrganizerEventDetails, props: true },

    // Admin
    { path: '/admin/dashboard', component: AdminDashboard },
    { path: '/admin/events', component: AdminManageEvents },
    { path: '/admin/users', component: ManageUsers },
    { path: '/admin/reports', component: AdminReports },
    

    { path: '/maintenance', component: UtilityMaintenance },
    { path: '*', component: NotFound }
  ]
})
