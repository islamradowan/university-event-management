import Vue from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import './assets/tailwind.css'
import http from './http'


Vue.config.productionTip = false
Vue.prototype.$http = http

// axios defaults
axios.defaults.baseURL = 'http://localhost:8000'   // your Laravel backend URL
axios.defaults.withCredentials = true             // needed for sanctum cookies

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
