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
