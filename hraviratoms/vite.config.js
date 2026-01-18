import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',

        // admin / общий
        'resources/js/app.js',

        // demo
        'resources/js/demo/main.js',

        // public request (ВАЖНО)
        'resources/js/invitation/app.js',
      ],
      refresh: true,
    }),
    vue(),
  ],
})
