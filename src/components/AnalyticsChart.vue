<template>
  <div class="bg-white p-4 rounded-lg shadow">
    <h3 class="text-lg font-semibold mb-4">{{ title }}</h3>
    <div class="h-64">
      <canvas :id="chartId" class="w-full h-full"></canvas>
    </div>
  </div>
</template>

<script>
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement
} from 'chart.js'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement
)

export default {
  name: 'AnalyticsChart',
  props: {
    title: { type: String, required: true },
    type: { type: String, default: 'line' },
    data: { type: Object, required: true },
    options: { type: Object, default: () => ({}) }
  },
  data() {
    return {
      chart: null,
      chartId: `chart-${Math.random().toString(36).substr(2, 9)}`
    }
  },
  mounted() {
    this.createChart()
  },
  watch: {
    data: {
      handler() {
        this.updateChart()
      },
      deep: true
    }
  },
  beforeDestroy() {
    if (this.chart) {
      this.chart.destroy()
    }
  },
  methods: {
    createChart() {
      const ctx = document.getElementById(this.chartId)
      if (!ctx) return

      const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
          }
        },
        scales: this.type !== 'pie' && this.type !== 'doughnut' ? {
          y: {
            beginAtZero: true
          }
        } : {}
      }

      this.chart = new ChartJS(ctx, {
        type: this.type,
        data: this.data,
        options: { ...defaultOptions, ...this.options }
      })
    },
    updateChart() {
      if (this.chart) {
        this.chart.data = this.data
        this.chart.update()
      }
    }
  }
}
</script>