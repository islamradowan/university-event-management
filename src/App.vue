<template>
  <div id="app" class="min-h-screen bg-slate-50 text-slate-800">
    <router-view />
    <RealTimeNotifications />
  </div>
</template>

<script>
import RealTimeNotifications from '@/components/RealTimeNotifications.vue'

export default {
  name: 'App',
  components: {
    RealTimeNotifications
  },

}
</script>

<style>
/* global small tweaks if needed */
</style>
