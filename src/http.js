import axios from 'axios'

const API_BASE = process.env.VUE_APP_API_BASE || 'http://localhost:8000'

// Simple cache for GET requests
const cache = new Map()
const CACHE_DURATION = 5 * 60 * 1000 // 5 minutes

const http = axios.create({
  baseURL: API_BASE,
  withCredentials: true,
  timeout: 10000
})

let csrfReady = false

async function ensureCsrf() {
  if (csrfReady) return
  try {
    await axios.get(API_BASE + '/sanctum/csrf-cookie', { withCredentials: true })
    csrfReady = true
  } catch (error) {
    csrfReady = false
    throw error
  }
}

// Reset CSRF on logout
export function resetCsrf() {
  csrfReady = false
}

// Request interceptor
http.interceptors.request.use(async (config) => {
  const method = (config.method || '').toLowerCase()
  const needsCsrf = ['post', 'put', 'patch', 'delete'].includes(method)
  const authPaths = ['/api/login', '/api/register', '/api/logout']

  // Check cache for GET requests
  if (method === 'get' && !config.skipCache) {
    const cacheKey = config.url + JSON.stringify(config.params || {})
    const cached = cache.get(cacheKey)
    if (cached && Date.now() - cached.timestamp < CACHE_DURATION) {
      return Promise.reject({ cached: cached.data, isCache: true })
    }
  }

  // Add cache control for GET requests only
  if (method === 'get') {
    config.headers['Cache-Control'] = 'max-age=300'
  }

  if (needsCsrf || authPaths.includes(config.url)) {
    await ensureCsrf()
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/)
    if (match) {
      config.headers['X-XSRF-TOKEN'] = decodeURIComponent(match[1])
    }
  }

  // Always refresh CSRF for auth endpoints
  if (authPaths.includes(config.url)) {
    resetCsrf()
  }

  return config
})

// Response interceptor
http.interceptors.response.use(
  (response) => {
    // Cache GET responses
    if (response.config.method === 'get' && !response.config.skipCache) {
      const cacheKey = response.config.url + JSON.stringify(response.config.params || {})
      cache.set(cacheKey, {
        data: response,
        timestamp: Date.now()
      })
    }
    return response
  },
  (error) => {
    // Handle cached responses
    if (error.isCache) {
      return Promise.resolve(error.cached)
    }
    return Promise.reject(error)
  }
)

export default http
