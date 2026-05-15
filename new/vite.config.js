// vite.config.js
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost:8088',
        changeOrigin: true,
      },
      '/stock-update': {
        target: 'http://localhost:8088/admin3295/stock-update.php',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/stock-update/, '')
      }
    }
  }
})