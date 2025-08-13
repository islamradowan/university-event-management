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

export default {
  name: 'OrganizerEventDetails',
  components: { Navbar, Footer },
  data() {
    return {
      event: { id:this.$route.params.id, title:'Web Dev Workshop', date:'2025-08-25', location:'Lab 3' },
      attendees: [ { name:'Rashed', email:'rashed@example.com', checked_in:false } ]
    }
  }
}
</script>
