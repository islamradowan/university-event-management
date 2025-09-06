const CACHE_NAME = 'uni-events-v1'
const API_CACHE = 'api-cache-v1'
const CACHE_DURATION = 5 * 60 * 1000 // 5 minutes

const urlsToCache = [
  '/',
  '/favicon.ico'
]

// Install event
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return Promise.all(
          urlsToCache.map(url => {
            return cache.add(url).catch(err => {
              console.warn('Failed to cache:', url, err)
            })
          })
        )
      })
      .then(() => self.skipWaiting())
  )
})

// Activate event
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME && cacheName !== API_CACHE) {
            return caches.delete(cacheName)
          }
        })
      )
    }).then(() => self.clients.claim())
  )
})

// Fetch event
self.addEventListener('fetch', event => {
  const { request } = event
  const url = new URL(request.url)

  // Skip non-http requests (chrome-extension, etc.)
  if (!url.protocol.startsWith('http')) {
    return
  }

  // Handle API requests
  if (url.pathname.startsWith('/api/')) {
    event.respondWith(handleApiRequest(request))
    return
  }

  // Handle static assets
  event.respondWith(
    caches.match(request)
      .then(response => {
        if (response) {
          return response
        }
        return fetch(request).then(response => {
          if (!response || response.status !== 200 || response.type !== 'basic') {
            return response
          }
          const responseToCache = response.clone()
          caches.open(CACHE_NAME)
            .then(cache => {
              if (request.url.startsWith('http')) {
                cache.put(request, responseToCache)
              }
            })
          return response
        })
      })
  )
})

// Handle API requests with cache
async function handleApiRequest(request) {
  try {
    const response = await fetch(request)
    
    // Cache successful GET requests
    if (response.ok && request.method === 'GET') {
      const cache = await caches.open(API_CACHE)
      const responseClone = response.clone()
      
      // Create new response with cache timestamp
      const headers = new Headers(responseClone.headers)
      headers.set('sw-cached-at', Date.now().toString())
      
      const cachedResponse = new Response(responseClone.body, {
        status: responseClone.status,
        statusText: responseClone.statusText,
        headers: headers
      })
      
      cache.put(request, cachedResponse)
    }
    
    return response
  } catch (error) {
    // Try to return cached response if network fails
    const cache = await caches.open(API_CACHE)
    const cachedResponse = await cache.match(request)
    
    if (cachedResponse) {
      const cachedTime = cachedResponse.headers.get('sw-cached-at')
      if (cachedTime && (Date.now() - parseInt(cachedTime)) < CACHE_DURATION) {
        return cachedResponse
      }
    }
    
    throw error
  }
}