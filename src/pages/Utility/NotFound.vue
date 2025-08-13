<template>
  <div class="min-h-screen flex items-center justify-center">
    <div class="text-center">
      <h1 class="text-6xl font-bold">404</h1>
      <p class="mt-2 text-slate-600">Page not found</p>
      <router-link to="/" class="mt-4 inline-block text-indigo-600">Go home</router-link>
    </div>
  </div>
</template>

<script>
export default { name: 'NotFound' }
</script>
