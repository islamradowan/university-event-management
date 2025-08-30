These are my frontend files (Vue.js 2)

**Vue Project Structure**
-------------------------------------------------------
/public/
    index.html
    favicon.ico

/src/
    /assets/
        tailwind.css
        logo.png
    /components/
        AdvancedSearch.vue          # Multi-criteria search with filters
        AppFooter.vue
        AppNavbar.vue               # Dynamic role-based navigation
        AvatarUpload.vue
        Breadcrumb.vue              # Hierarchical navigation
        EventCalendar.vue           # FullCalendar integration
        EventCard.vue
        FileUpload.vue
        LiveAttendanceTracker.vue   # Real-time attendance tracking
        LiveEventUpdates.vue        # Real-time event updates
        Modal.vue
        Pagination.vue              # Reusable pagination component
        QRCodeDisplay.vue
        QRScanner.vue
        RealTimeNotifications.vue   # Real-time notifications
        SearchBar.vue
    /pages/
        /Admin/
            AdminManageEvents.vue
            AdminReports.vue
            Calendar.vue            # Admin calendar view
            Dashboard.vue
            ManageUser.vue
            SendAnnouncement.vue
        /Organizer/
            Calendar.vue            # Organizer calendar view
            CreateEvent.vue
            Dashboard.vue
            EditEvent.vue
            EventDetails.vue        # With live attendance tracking
        /Public/
            PublicLanding.vue
            PublicLogin.vue
            PublicRegister.vue
        /Student/
            Calendar.vue            # Student calendar view
            Dashboard.vue           # With real-time features
            EventDetails.vue
            MyEvents.vue
            SearchEvents.vue        # Advanced search page
        /Profile/
            UserProfile.vue
        /Utility/
            NotFound.vue
            UtilityMaintenance.vue
    /plugins/
        pusher.js                   # Pusher configuration
    /router/
        index.js                    # Route guards & role-based routing
    /store/
        index.js                    # Vuex store with API actions
    /utils/
        apiMappers.js
    App.vue
    main.js
    http.js                         # Axios configuration with CSRF
    api.js

Root files:
    .env                            # Environment variables
    package.json                    # Dependencies with pusher-js
    tailwind.config.js
    postcss.config.js
    vue.config.js
    babel.config.js
    .eslintrc.js
--------------------------------------------------------------

** files **
-----------------------------------------------------------------
    
/components/AppFooter.vue
-------------------------------------------------------------------------
 <template>
  <footer class="text-center p-6 text-sm text-slate-500">
     Built for Laravel backend · Tailwind demo UI
    </footer>
  </template>

<script>
 export default { name: 'AppFooter' }
</script>
-----------------------------------------------------------------------

/components/AppNavbar.vue
-----------------------------------------------------------------------
        <template>
  <header class="bg-white shadow-sm">
    <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
      <router-link to="/" class="text-xl font-semibold">UniEvents</router-link>

      <div class="flex items-center space-x-3">
        <SearchBar v-if="showSearch" @search="onSearch" />

        <nav class="hidden sm:flex items-center space-x-3 text-sm">
          <router-link to="/" class="px-2 py-1 hover:bg-slate-100 rounded">Home</router-link>
          <router-link to="/student/dashboard" class="px-2 py-1 hover:bg-slate-100 rounded">Student</router-link>
          <router-link to="/organizer/dashboard" class="px-2 py-1 hover:bg-slate-100 rounded">Organizer</router-link>
          <router-link to="/admin/dashboard" class="px-2 py-1 hover:bg-slate-100 rounded">Admin</router-link>
        </nav>

        <div class="flex items-center space-x-2">
          <router-link to="/login" class="text-sm bg-indigo-600 text-white px-3 py-1 rounded">Sign in</router-link>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import SearchBar from './SearchBar.vue'

export default {
  name: 'AppNavbar',
  components: { SearchBar },
  props: {
    showSearch: { type: Boolean, default: true }
  },
  methods: {
    onSearch(q) {
      this.$emit('search', q)
    }
  }
}
</script>
---------------------------------------------------------------------

/components/EventCard.vue
---------------------------------------------------------------------
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
------------------------------------------------------------------------

/components/Modal.vue
-------------------------------------------------------------------------
      <template>
  <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black opacity-50" @click="close"></div>
    <div class="bg-white rounded-lg shadow-lg z-50 max-w-3xl w-full mx-4 overflow-auto">
      <div class="p-4">
        <slot />
        <div class="flex justify-end mt-4">
          <button @click="close" class="px-3 py-1 border rounded">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Modal',
  props: { visible: { type: Boolean, default: false } },
  methods: {
    close() { this.$emit('update:visible', false); this.$emit('close') }
  }
}
</script>
--------------------------------------------------------------------

/components/SearchBar.vue
-------------------------------------------------------------------------
        <template>
  <div class="relative">
    <input v-model="q" @input="emit" placeholder="Search events..." class="border rounded px-3 py-2 text-sm" />
  </div>
</template>

<script>
export default {
  name: 'SearchBar',
  data() { return { q: '' } },
  methods: {
    emit() { this.$emit('search', this.q) }
  }
}
</script>
----------------------------------------------------------------------
    
/pages/Admin/AdminManageEvents.vue
------------------------------------------------------------------------
      <template>
  <div>
    <Navbar />
    <main class="max-w-6xl mx-auto p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Pending Events</h1>
        <div class="flex gap-2">
          <router-link to="/admin/dashboard" class="text-sm border px-3 py-1 rounded">Dashboard</router-link>
          <router-link to="/admin/users" class="text-sm border px-3 py-1 rounded">Manage Users</router-link>
          <router-link to="/admin/reports" class="text-sm border px-3 py-1 rounded">Reports</router-link>
        </div>
      </div>
      
      <div class="bg-white p-4 rounded shadow">
        <div v-for="e in pending" :key="e.id" class="flex justify-between items-center py-3 border-b last:border-b-0">
          <div>
            <h3 class="font-semibold">{{ e.title }}</h3>
            <p class="text-sm text-slate-600">Submitted by: {{ e.organizer }}</p>
          </div>
          <div class="space-x-2">
            <button @click="approve(e.id)" class="px-3 py-1 border rounded">Approve</button>
            <button @click="reject(e.id)" class="px-3 py-1 border rounded">Reject</button>
          </div>
        </div>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'

export default {
  name: 'AdminManageEvents',
  components: { Navbar, Footer },
  data() {
    return { 
      pending: [ 
        {id: 1, title: 'New Workshop Proposal', organizer: 'Rashed'},
        {id: 2, title: 'Tech Conference', organizer: 'Alex'}
      ] 
    }
  },
  methods: {
    async approve(id) {
      // PUT /api/events/:id/status {status:'approved'}
      alert(`Approved event ID: ${id}`)
      // In a real app, you would make an API call here
      // await this.$http.put(`/api/events/${id}/status`, { status: 'approved' })
      // Then remove from pending list or refresh data
    },
    async reject(id) {
      alert(`Rejected event ID: ${id}`)
      // await this.$http.put(`/api/events/${id}/status`, { status: 'rejected' })
    }
  }
}
</script>
-------------------------------------------------------------------------
    
/pages/Admin/AdminReports.vue
-------------------------------------------------------------------------
      <template>
  <div>
    <Navbar />
    <main class="max-w-4xl mx-auto p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Export Reports</h1>
        <div class="flex gap-2">
          <router-link to="/admin/dashboard" class="text-sm border px-3 py-1 rounded">Dashboard</router-link>
          <router-link to="/admin/events" class="text-sm border px-3 py-1 rounded">Events</router-link>
          <router-link to="/admin/users" class="text-sm border px-3 py-1 rounded">Manage Users</router-link>
        </div>
      </div>
      
      <div class="bg-white p-4 rounded shadow">
        <form @submit.prevent="exportCSV">
          <label class="text-sm">Date range</label>
          <div class="grid grid-cols-2 gap-2 mt-2">
            <input v-model="from" type="date" class="border rounded px-3 py-2" />
            <input v-model="to" type="date" class="border rounded px-3 py-2" />
          </div>
          <div class="flex justify-end mt-3">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Export CSV</button>
          </div>
        </form>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'

export default {
  name: 'AdminReports',
  components: { Navbar, Footer },
  data() { return { from:'', to:'' } },
  methods: {
    exportCSV() {
      // GET /admin/export-registrations.csv?from=&to=
      alert('Export CSV (demo)')
    }
  }
}
</script>
----------------------------------------------------------------------

/pages/Admin/Dashboard.vue
-------------------------------------------------------------------------
      <template>
  <div>
    <Navbar />
    <main class="max-w-6xl mx-auto p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Overview</h1>
        <div class="flex gap-2">
          <router-link to="/admin/events" class="text-sm border px-3 py-1 rounded">Events</router-link>
          <router-link to="/admin/users" class="text-sm border px-3 py-1 rounded">Manage Users</router-link>
          <router-link to="/admin/reports" class="text-sm border px-3 py-1 rounded">Reports</router-link>
        </div>
      </div>
 
      <div class="grid grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded shadow text-center">
          <div class="text-3xl font-bold">{{ stats.events }}</div>
          <div class="text-sm text-slate-600">Events</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
          <div class="text-3xl font-bold">{{ stats.users }}</div>
          <div class="text-sm text-slate-600">Users</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
          <div class="text-3xl font-bold">{{ stats.registrations }}</div>
          <div class="text-sm text-slate-600">Registrations</div>
        </div>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'

export default {
  name: 'AdminDashboard',
  components: { Navbar, Footer },
  data() {
    return { stats: { events:120, users:4200, registrations:8910 } }
  }
}
</script>
---------------------------------------------------------------------

/pages/Admin/ManageUser.vue
--------------------------------------------------------------------
      <template>
  <div>
    <Navbar />
    <main class="max-w-6xl mx-auto p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">User Management</h1>
        <div class="flex gap-2">
          <router-link to="/admin/dashboard" class="text-sm border px-3 py-1 rounded">Dashboard</router-link>
          <router-link to="/admin/events" class="text-sm border px-3 py-1 rounded">Events</router-link>
          <router-link to="/admin/reports" class="text-sm border px-3 py-1 rounded">Reports</router-link>
        </div>
      </div>

      <div class="bg-white p-4 rounded shadow">
        <table class="w-full text-left text-sm">
          <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead>
          <tbody>
            <tr v-for="u in users" :key="u.email">
              <td>{{ u.name }}</td><td>{{ u.email }}</td><td>{{ u.role }}</td>
              <td><button class="px-2 py-1 border rounded">Edit</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'

export default {
  name: 'ManageUsers',
  components: { Navbar, Footer },
  data() {
    return { users: [ {name:'Rashed', email:'rashed@example.com', role:'Organizer'} ] }
  }
}
</script>
---------------------------------------------------------------------

/pages/Organizer/CreateEvent.vue
--------------------------------------------------------------------
<template>
  <div>
    <Navbar />
    <main class="max-w-3xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Create Event</h1>
      <form @submit.prevent="create" class="bg-white p-4 rounded shadow space-y-3">
        <input v-model="form.title" placeholder="Event title" required class="w-full border rounded px-3 py-2" />
        <div class="grid grid-cols-2 gap-2">
          <input v-model="form.date" type="date" required class="border rounded px-3 py-2" />
          <input v-model="form.time" type="time" required class="border rounded px-3 py-2" />
        </div>
        <input v-model="form.location" placeholder="Location" class="w-full border rounded px-3 py-2" />
        <select v-model="form.category" class="w-full border rounded px-3 py-2">
          <option>Workshop</option><option>Seminar</option><option>Cultural</option>
        </select>
        <textarea v-model="form.description" placeholder="Description" class="w-full border rounded px-3 py-2 h-32"></textarea>
        <div class="flex justify-end">
          <button class="bg-indigo-600 text-white px-4 py-2 rounded">Submit for Approval</button>
        </div>
      </form>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import { formToEventPayload } from '@/utils/apiMappers'

export default {
  name: 'CreateEvent',
  components: { Navbar, Footer },
  data() {
    return { form: { title:'', date:'', time:'', location:'', category:'Workshop', description:'' } }
  },
  methods: {
    async create() {
      try {
        await this.$http.post('/api/events', formToEventPayload(this.form))
        alert('Event created')
        this.$router.push('/organizer/dashboard')
      } catch (err) {
        console.error(err)
        alert('Create failed')
      }
    }
  }
}
</script>

-----------------------------------------------------------------

/pages/Organizer/Dashboard.vue
------------------------------------------------------------------
<template>
  <div>
    <Navbar />
    <main class="max-w-6xl mx-auto p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Your Events</h1>
        <router-link to="/organizer/create" class="text-sm border px-3 py-1 rounded">Create Event</router-link>
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        <div v-for="e in events" :key="e.id" class="bg-white p-4 rounded shadow">
          <h3 class="font-semibold">{{ e.title }}</h3>
          <p class="text-sm text-slate-600">{{ e.date }} · {{ e.location }}</p>
          <div class="mt-3 flex gap-2">
            <router-link :to="`/organizer/event/${e.id}`" class="text-sm border px-3 py-1 rounded">Manage</router-link>
            <router-link :to="`/organizer/edit/${e.id}`" class="text-sm border px-3 py-1 rounded">Edit</router-link>
          </div>
        </div>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import { apiEventToView } from '@/utils/apiMappers'

export default {
  name: 'OrganizerDashboard',
  components: { Navbar, Footer },
  data() {
    return { events: [] }
  },
  async created() {
    try {
      const apiEvents = await this.$store.dispatch('fetchMyEvents')
      this.events = apiEvents.map(apiEventToView)
    } catch (err) {
      console.error(err)
      alert('Failed to load events')
    }
  }
}
</script>

----------------------------------------------------------------

/pages/Organizer/EditEvent.vue
----------------------------------------------------------------
<template>
  <div>
    <Navbar />
    <main class="max-w-3xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Edit Event</h1>
      <form @submit.prevent="save" class="bg-white p-4 rounded shadow space-y-3">
        <input v-model="form.title" required class="w-full border rounded px-3 py-2" />
        <div class="grid grid-cols-2 gap-2">
          <input v-model="form.date" type="date" required class="border rounded px-3 py-2" />
          <input v-model="form.time" type="time" required class="border rounded px-3 py-2" />
        </div>
        <input v-model="form.location" class="w-full border rounded px-3 py-2" />
        <select v-model="form.category" class="w-full border rounded px-3 py-2">
          <option>Workshop</option><option>Seminar</option><option>Cultural</option>
        </select>
        <textarea v-model="form.description" class="w-full border rounded px-3 py-2 h-32"></textarea>
        <div class="flex justify-between">
          <button class="px-4 py-2 border rounded">Save draft</button>
          <button class="bg-indigo-600 text-white px-4 py-2 rounded">Save changes</button>
        </div>
      </form>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import { apiEventToView, formToEventPayload } from '@/utils/apiMappers'

export default {
  name: 'EditEvent',
  components: { Navbar, Footer },
  data() {
    return { form: { title:'', date:'', time:'', location:'', category:'Workshop', description:'' } }
  },
  async created() {
    const apiEvent = await this.$store.dispatch('fetchEvent', this.$route.params.id)
    const view = apiEventToView(apiEvent)
    this.form = {
      title: view.title,
      date: view.date,
      time: view.time,
      location: view.location || '',
      category: view.category || 'Workshop',
      description: view.description || ''
    }
  },
  methods: {
    async save() {
      try {
        const payload = formToEventPayload(this.form)
        await this.$store.dispatch('updateEvent', { id: this.$route.params.id, payload })
        alert('Saved')
        this.$router.push('/organizer/dashboard')
      } catch (err) { console.error(err); alert('Save failed') }
    }
  }
}
</script>

-------------------------------------------------------------------

/pages/Organizer/EventDetails.vue
----------------------------------------------------------------        
<template>
  <div>
    <Navbar />
    <main class="max-w-4xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-2">{{ event.title }}</h1>
      <p class="text-sm text-slate-600">{{ event.date }} · {{ event.location }}</p>

      <section class="bg-white p-4 rounded shadow mt-4">
        <h3 class="font-semibold mb-2">Attendees</h3>
        <table class="w-full text-left text-sm">
          <thead><tr><th>Name</th><th>Email</th><th>Checked in</th></tr></thead>
          <tbody>
            <tr v-for="a in attendees" :key="a.email">
              <td>{{ a.name }}</td><td>{{ a.email }}</td><td>{{ a.checked_in ? 'Yes':'No' }}</td>
            </tr>
          </tbody>
        </table>
      </section>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import { apiEventToView } from '@/utils/apiMappers'

export default {
  name: 'OrganizerEventDetails',
  components: { Navbar, Footer },
  data() {
    return { event: null, attendees: [] }
  },
  async created() {
    const apiEvent = await this.$store.dispatch('fetchEvent', this.$route.params.id)
    this.event = apiEventToView(apiEvent)
    // assumes backend loads registrations with user relationship
    this.attendees = (apiEvent.registrations || []).map(r => ({
      name: r.user?.name || '-',
      email: r.user?.email || '-',
      checked_in: !!r.checked_in_at,
      id: r.id
    }))
  },
  methods: {
    async markCheckIn(reg) {
      await this.$store.dispatch('checkIn', reg.id)
      reg.checked_in = true
    }
  }
}
</script>

------------------------------------------------------------

/pages/Public/PublicLacding.vue
---------------------------------------------------------------
      <template>
  <div>
    <Navbar />
    <header class="bg-indigo-600 text-white py-12">
      <div class="max-w-6xl mx-auto text-center px-4">
        <h1 class="text-4xl font-bold mb-2">University Event Management</h1>
        <p class="text-lg mb-6">Browse, register and manage university events.</p>
        <router-link to="/login" class="bg-white text-indigo-600 px-6 py-3 rounded-lg shadow">Get Started</router-link>
      </div>
    </header>

    <main class="max-w-6xl mx-auto p-6 grid lg:grid-cols-3 gap-6">
      <section class="lg:col-span-2">
        <h2 class="text-2xl font-semibold mb-4">Upcoming Events</h2>
        <div class="grid sm:grid-cols-2 gap-4">
          <EventCard v-for="e in events" :key="e.id" :event="e" />
        </div>
      </section>

      <aside>
        <div class="bg-white p-4 rounded shadow mb-4">
          <h4 class="font-semibold">Featured</h4>
          <div class="h-40 bg-slate-100 rounded mt-3"></div>
        </div>
        <div class="bg-white p-4 rounded shadow">
          <h4 class="font-semibold">Quick Links</h4>
          <ul class="mt-2 text-sm text-slate-600">
            <li><router-link to="/student/dashboard">Student dashboard</router-link></li>
            <li><router-link to="/organizer/dashboard">Organizer</router-link></li>
            <li><router-link to="/admin/dashboard">Admin</router-link></li>
          </ul>
        </div>
      </aside>
    </main>

    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import EventCard from '@/components/EventCard.vue'

export default {
  name: 'PublicLanding',
  components: { Navbar, Footer, EventCard },
  data() {
    return {
      events: [
        { id:1, title:'AI Ethics Seminar', date:'2025-09-10', time:'15:00', location:'Auditorium A', description:'A talk about ethics in AI.', category:'Seminar', status:'approved' },
        { id:2, title:'Cultural Night', date:'2025-09-20', time:'19:30', location:'Main Stage', description:'Music and dance.', category:'Cultural', status:'approved' }
      ]
    }
  }
}
</script>
------------------------------------------------------------------

/pages/Public/PublicLogin.vue
-----------------------------------------------------------------
<template>
  <div>
    <Navbar :showSearch="false" />
    <div class="min-h-screen flex items-center justify-center">
      <div class="max-w-md w-full bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Sign in to UniEvents</h2>
        <form @submit.prevent="login" class="space-y-3">
          <input v-model="email" type="email" placeholder="Email" required class="w-full border rounded px-3 py-2" />
          <input v-model="password" type="password" placeholder="Password" required class="w-full border rounded px-3 py-2" />
          <div class="flex items-center justify-between text-sm">
            <label><input type="checkbox" class="mr-2" /> Remember me</label>
            <router-link to="/register" class="text-indigo-600">Register</router-link>
          </div>
          <button class="w-full bg-indigo-600 text-white px-4 py-2 rounded">Login</button>
        </form>
      </div>
    </div>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'

export default {
  name: 'PublicLogin',
  components: { Navbar, Footer },
  data() { return { email:'', password:'' } },
  methods: {
    async login() {
      try {
        // 1. Hit Laravel Sanctum login endpoint
        await this.$http.post('/api/login', { 
          email: this.email, 
          password: this.password 
        })

        // 2. Get authenticated user data from backend
        await this.$store.dispatch('fetchUser')

        // 3. Redirect based on role
        const role = this.$store.getters.userRole
        if (role === 'admin') {
          this.$router.push('/admin/dashboard')
        } else if (role === 'organizer') {
          this.$router.push('/organizer/dashboard')
        } else if (role === 'student') {
          this.$router.push('/student/dashboard')
        } else {
          // fallback if role not found
          this.$router.push('/')
        }

      } catch (err) {
        console.error(err.response?.data || err.message)
        // optionally show a toast / error message
      }
    }
  }
}
</script>

-----------------------------------------------------------------

/pages/Public/PublicRegister.vue
------------------------------------------------------------------
<template>
  <div>
    <Navbar :showSearch="false" />
    <div class="min-h-screen flex items-center justify-center">
      <div class="max-w-md w-full bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Create an account</h2>
        <form @submit.prevent="register" class="space-y-3">
          <input v-model="name" placeholder="Full name" required class="w-full border rounded px-3 py-2" />
          <input v-model="email" type="email" placeholder="Email" required class="w-full border rounded px-3 py-2" />
          <input v-model="password" type="password" placeholder="Password" required class="w-full border rounded px-3 py-2" />
          <select v-model="role" class="w-full border rounded px-3 py-2">
            <option value="student">Student</option>
            <option value="organizer">Organizer</option>
          </select>
          <button class="w-full bg-indigo-600 text-white px-4 py-2 rounded">Register</button>
        </form>
      </div>
    </div>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'

export default {
  name: 'PublicRegister',
  components: { Navbar, Footer },
  data() { return { name:'', email:'', password:'', role:'student' } },
  methods: {
    async register() {
      try {
        const res = await this.$http.post('/api/register', { name:this.name, email:this.email, password:this.password, role:this.role })
        console.log(res.data)
        this.$router.push('/login')
        // await this.$store.dispatch('fetchUser')
        // const role = this.$store.getters.userRole
        // this.$router.push(role === 'admin' ? '/admin/dashboard' 
        //   : role === 'organizer' ? '/organizer/dashboard' 
        //   : '/student/dashboard')
      } catch (err) {
        console.error(err)
        alert('Register failed')
      }
    }
  }
}
</script>

-------------------------------------------------------------------

/pages/Student/Dashboard.vue
-------------------------------------------------------------------
<template>
  <div>
    <Navbar @search="onSearch" />
    <main class="max-w-6xl mx-auto p-6">
      <header class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Events for you</h1>
        <router-link to="/student/my-events" class="text-sm border px-3 py-1 rounded">My Events</router-link>
      </header>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <EventCard v-for="e in filtered" :key="e.id" :event="e" />
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import EventCard from '@/components/EventCard.vue'
import { apiEventToView } from '@/utils/apiMappers'

export default {
  name: 'StudentDashboard',
  components: { Navbar, Footer, EventCard },
  data() {
    return { events: [], q: '' }
  },
  async created() {
    const apiEvents = await this.$store.dispatch('fetchEvents')
    this.events = apiEvents.map(apiEventToView)
    console.log(this.events)
  },
  computed: {
    filtered() {
      if (!this.q) return this.events
      const q = this.q.toLowerCase()
      return this.events.filter(e => (e.title + e.description + e.location).toLowerCase().includes(q))
    }
  },
  methods: { onSearch(q) { this.q = q } }
}
</script>


---------------------------------------------------------------

/pages/Student/EventDetails.vue
--------------------------------------------------------------
<template>
  <div>
    <Navbar />
    <main class="max-w-4xl mx-auto p-6">
      <div class="bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold">{{ event.title }}</h1>
        <p class="text-sm text-slate-600">{{ event.date }} · {{ event.time }} · {{ event.location }}</p>
        <div class="h-48 bg-slate-100 rounded my-4"></div>
        <p class="text-slate-700">{{ event.description }}</p>
        <div class="mt-6 flex gap-3">
          <button @click="register" class="bg-indigo-600 text-white px-4 py-2 rounded">Register</button>
          <button @click="addCalendar" class="px-4 py-2 border rounded">Add to calendar</button>
        </div>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import { apiEventToView } from '@/utils/apiMappers'

export default {
  name: 'StudentEventDetails',
  components: { Navbar, Footer },
  data() {
     return { event: null }
  },
  async created() {
    const apiEvent = await this.$store.dispatch('fetchEvent', this.$route.params.id)
    this.event = apiEventToView(apiEvent)
  },
  methods: {
    async register() {
      try {
        await this.$store.dispatch('registerForEvent', this.event.id)
        alert('Registered! Check “My Events”.')
      } catch (err) {
        console.error(err); alert('Register failed')
      }
    },
    addCalendar() { /* optional */ }
  }
}
</script>

------------------------------------------------------------------

/pages/Student/MyEvents.vue
-------------------------------------------------------------------    
<template>
  <div>
    <Navbar />
    <main class="max-w-6xl mx-auto p-6">
      <h2 class="text-xl font-semibold mb-4">Events you registered for</h2>
      <div class="grid sm:grid-cols-2 gap-4">
        <div v-for="e in myEvents" :key="e.id" class="bg-white p-4 rounded shadow">
          <h3 class="font-semibold">{{ e.title }}</h3>
          <p class="text-sm text-slate-600">{{ e.date }} · {{ e.location }}</p>
          <div class="mt-3 flex justify-between items-center">
            <router-link :to="`/student/event/${e.id}`" class="text-sm text-indigo-600">Details</router-link>
            <button @click="showQR(e)" class="text-sm border px-3 py-1 rounded">Show QR</button>
          </div>
        </div>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/components/AppNavbar.vue'
import Footer from '@/components/AppFooter.vue'
import { apiEventToView } from '@/utils/apiMappers'

export default {
  name: 'MyEvents',
  components: { Navbar, Footer },
  data() { return { myEvents: [] } },
  async created() {
    const regs = await this.$store.dispatch('fetchMyRegistrations')
    // assume API returns registrations with nested event
    this.myEvents = regs.map(r => apiEventToView(r.event))
  },
  methods: {
    showQR(reg) {
      // If backend returns `qr_token` on registration, display/QR-encode it.
      alert(`QR token: ${reg.qr_token || 'N/A'}`)
    }
  }
}
</script>

----------------------------------------------------------------------

/router/index.js
---------------------------------------------------------------------
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

------------------------------------------------------------------------

/store/index.js
---------------------------------------------------------------------
import Vue from 'vue'
import Vuex from 'vuex'
import http from '../http'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: null,
  },
  mutations: {
    setUser(state, user) {
      state.user = user
    },
    clearUser(state) {
      state.user = null
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

    // Events (read)
    async fetchEvents() {
      const res = await http.get('/api/events')
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

---------------------------------------------------------------------------

utils/apiMappers.js
---------------------------------------------------------------------------
// Combines {date:'YYYY-MM-DD', time:'HH:mm'} into ISO strings
export function formToEventPayload(form) {
  const start_at = `${form.date} ${form.time}:00`
  const end_at = form.endDate && form.endTime ? `${form.endDate} ${form.endTime}:00` : null
  
  return {
    title: form.title,
    description: form.description,
    start_at,
    end_at,
    location: form.location,
    category: form.category,
    capacity: form.capacity,
    featured: form.featured || false
  }
}

// Converts API event to view format
export function apiEventToView(apiEvent) {
  return {
    id: apiEvent.id,
    title: apiEvent.title,
    description: apiEvent.description,
    date: apiEvent.start_at?.split(' ')[0] || '',
    time: apiEvent.start_at?.split(' ')[1]?.slice(0, 5) || '',
    start_at: apiEvent.start_at,
    end_at: apiEvent.end_at,
    location: apiEvent.location,
    category: apiEvent.category,
    status: apiEvent.status,
    capacity: apiEvent.capacity,
    featured: apiEvent.featured,
    organizer: apiEvent.organizer?.name || 'Unknown'
  }
}

  // If no separate end date/time, add 2 hours to local start time
  let end_at
  if (form.end_date && form.end_time) {
    end_at = `${form.end_date} ${form.end_time}:00`
  } else {
    const startDateObj = new Date(`${form.date}T${form.time}`)
    startDateObj.setHours(startDateObj.getHours() + 2)

    // Convert back to local date/time string
    const yyyy = startDateObj.getFullYear()
    const mm = String(startDateObj.getMonth() + 1).padStart(2, '0')
    const dd = String(startDateObj.getDate()).padStart(2, '0')
    const hh = String(startDateObj.getHours()).padStart(2, '0')
    const min = String(startDateObj.getMinutes()).padStart(2, '0')

    end_at = `${yyyy}-${mm}-${dd} ${hh}:${min}:00`
  }

  return {
    title: form.title,
    description: form.description || null,
    start_at,
    end_at,
    location: form.location || null,
    category: form.category || null,
    status: 'pending',
    capacity: form.capacity ? Number(form.capacity) : null,
    featured: !!form.featured
  }
}

// For displaying an event read from API
export function apiEventToView(e) {
  const start = new Date(e.start_at)
  return {
    ...e,
    date: start.toISOString().slice(0, 10),
    time: start.toTimeString().slice(0, 5)
  }
}

-------------------------------------------------------------------------------

src/http.js
-----------------------------------------------------------------
import axios from 'axios'

// .env in Vue: VUE_APP_API_BASE=http://localhost:8000 (or leave default below)
const API_BASE = process.env.VUE_APP_API_BASE || 'http://localhost:8000'

// Create Axios instance
const http = axios.create({
  baseURL: process.env.VUE_APP_API_BASE,
  withCredentials: true // send cookies
})

let csrfReady = false

async function ensureCsrf() {
  if (csrfReady) return
  await axios.get(API_BASE + '/sanctum/csrf-cookie', { withCredentials: true })
  csrfReady = true
}

// Interceptor: ensure CSRF cookie exists AND set header
http.interceptors.request.use(async (config) => {
  const method = (config.method || '').toLowerCase()
  const needsCsrf = ['post', 'put', 'patch', 'delete'].includes(method)
  const authPaths = ['/api/login', '/api/register', '/api/logout']

  if (needsCsrf || authPaths.includes(config.url)) {
    await ensureCsrf()

    // Get CSRF token from cookie and attach to header
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/)
    if (match) {
      const token = decodeURIComponent(match[1])
      config.headers['X-XSRF-TOKEN'] = token
    }
  }

  return config
})

export default http

-----------------------------------------------------------------------

src/main.js
----------------------------------------------------------------------
import Vue from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import './assets/tailwind.css'
import http from './http'
import store from './store'

Vue.config.productionTip = false
Vue.prototype.$http = http

// axios defaults
axios.defaults.baseURL = 'http://localhost:8000'   // your Laravel backend URL
axios.defaults.withCredentials = true             // needed for sanctum cookies
axios.prototype.$http = http

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')

--------------------------------------------------------------------------------

use these only tailwindcss@^3 + vue 2 + laravel 10 + mysql