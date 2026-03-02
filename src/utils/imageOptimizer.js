// Image optimization utilities
export const optimizeImageUrl = (url, options = {}) => {
  if (!url) return null
  
  const { width = 400, quality = 80, format = 'webp' } = options
  const separator = url.includes('?') ? '&' : '?'
  
  // Add optimization parameters for server-side processing
  return `${url}${separator}w=${width}&q=${quality}&f=${format}`
}

// Lazy loading observer
export class LazyImageObserver {
  constructor() {
    this.observer = null
    this.init()
  }
  
  init() {
    if ('IntersectionObserver' in window) {
      this.observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target
            if (img.dataset.src) {
              img.src = img.dataset.src
              img.removeAttribute('data-src')
              this.observer.unobserve(img)
            }
          }
        })
      }, { rootMargin: '50px' })
    }
  }
  
  observe(element) {
    if (this.observer) {
      this.observer.observe(element)
    }
  }
  
  disconnect() {
    if (this.observer) {
      this.observer.disconnect()
    }
  }
}

// Global lazy image instance
export const lazyImageObserver = new LazyImageObserver()