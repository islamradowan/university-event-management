import Vue from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import './assets/tailwind.css'
import http from './http'
import store from './store'
import { registerSW } from './utils/serviceWorker'

Vue.config.productionTip = false
Vue.prototype.$http = http

// axios defaults
axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true

// Register service worker
registerSW()

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
