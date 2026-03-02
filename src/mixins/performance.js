export const loadingMixin = {
  data() {
    return {
      loading: false
    }
  },
  methods: {
    async withLoading(asyncFn) {
      this.loading = true
      try {
        return await asyncFn()
      } finally {
        this.loading = false
      }
    }
  }
}