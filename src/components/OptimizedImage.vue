<template>
  <img
    ref="image"
    :src="placeholderSrc"
    :data-src="optimizedSrc"
    :alt="alt"
    :class="imageClass"
    @load="onLoad"
    @error="onError"
  />
</template>

<script>
import { optimizeImageUrl, lazyImageObserver } from '@/utils/imageOptimizer'

export default {
  name: 'OptimizedImage',
  props: {
    src: {
      type: String,
      required: true
    },
    alt: {
      type: String,
      default: ''
    },
    width: {
      type: Number,
      default: 400
    },
    quality: {
      type: Number,
      default: 80
    },
    lazy: {
      type: Boolean,
      default: true
    },
    placeholder: {
      type: String,
      default: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0yMCAyNkMyMy4zMTM3IDI2IDI2IDIzLjMxMzcgMjYgMjBDMjYgMTYuNjg2MyAyMy4zMTM3IDE0IDIwIDE0QzE2LjY4NjMgMTQgMTQgMTYuNjg2MyAxNCAyMEMxNCAyMy4zMTM3IDE2LjY4NjMgMjYgMjAgMjZaIiBmaWxsPSIjOUI5QkEwIi8+Cjwvc3ZnPgo='
    }
  },
  computed: {
    optimizedSrc() {
      return optimizeImageUrl(this.src, {
        width: this.width,
        quality: this.quality
      })
    },
    placeholderSrc() {
      return this.lazy ? this.placeholder : this.optimizedSrc
    },
    imageClass() {
      return {
        'opacity-0': this.lazy && !this.loaded,
        'opacity-100': !this.lazy || this.loaded,
        'transition-opacity': true,
        'duration-300': true
      }
    }
  },
  data() {
    return {
      loaded: false
    }
  },
  mounted() {
    if (this.lazy) {
      lazyImageObserver.observe(this.$refs.image)
    }
  },
  beforeDestroy() {
    if (this.lazy && this.$refs.image) {
      lazyImageObserver.observer?.unobserve(this.$refs.image)
    }
  },
  methods: {
    onLoad() {
      this.loaded = true
      this.$emit('load')
    },
    onError() {
      this.$emit('error')
    }
  }
}
</script>