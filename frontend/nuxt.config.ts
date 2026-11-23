export default defineNuxtConfig({
  // Запросы к /api Nuxt пересылает на Laravel — браузер ходит только на один адрес, поэтому CORS не нужен
  routeRules: {
    '/api/**': { proxy: 'http://127.0.0.1:8000/api/**' },
  },
})
