const { defineConfig } = require('@vue/cli-service')

module.exports = defineConfig({
  transpileDependencies: true,
  
  // Development server configuration
  devServer: {
    client: {
      webSocketURL: 'auto://0.0.0.0:0/ws'
    }
  },
  
  // PWA configuration
  pwa: {
    workboxPluginMode: 'InjectManifest',
    workboxOptions: {
      swSrc: 'public/sw.js'
    }
  },
  
  configureWebpack: {
    optimization: {
      splitChunks: {
        chunks: 'all',
        maxInitialRequests: 10,
        maxAsyncRequests: 10,
        cacheGroups: {
          vendor: {
            test: /[\\/]node_modules[\\/]/,
            name: 'vendors',
            chunks: 'all',
            priority: 10
          },
          fullcalendar: {
            test: /[\\/]node_modules[\\/]@?fullcalendar[\\/]/,
            name: 'fullcalendar',
            chunks: 'all',
            priority: 20
          },
          chartjs: {
            test: /[\\/]node_modules[\\/]chart\.js[\\/]/,
            name: 'chartjs',
            chunks: 'all',
            priority: 15
          },
          common: {
            name: 'common',
            minChunks: 2,
            chunks: 'all',
            priority: 5
          }
        }
      }
    }
  },
  
  chainWebpack: config => {
    if (process.env.NODE_ENV === 'production') {
      // Remove console logs in production
      config.optimization.minimizer('terser').tap(args => {
        args[0].terserOptions.compress.drop_console = true
        args[0].terserOptions.compress.drop_debugger = true
        return args
      })
    }
  }
})