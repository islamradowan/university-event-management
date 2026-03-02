// Basic utilities
export const debounce = (func, wait) => {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

export const throttle = (func, limit) => {
  let inThrottle
  return function() {
    const args = arguments
    const context = this
    if (!inThrottle) {
      func.apply(context, args)
      inThrottle = true
      setTimeout(() => inThrottle = false, limit)
    }
  }
}

// Image optimization helper
export const optimizeImageUrl = (url, width = 400, quality = 80) => {
  if (!url) return null
  const separator = url.includes('?') ? '&' : '?'
  return `${url}${separator}w=${width}&q=${quality}`
}