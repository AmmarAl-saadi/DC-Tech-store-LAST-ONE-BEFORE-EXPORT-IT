
import {defineNuxtConfig} from 'nuxt/config'
import configJson from './jsConfig.json'

export default defineNuxtConfig({
  nitro: {
    preset: 'node-server',
    routeRules: {
      '/robots.txt': { prerender: false },
      '/sitemap.xml': { prerender: false },
    },
    handlers: [
      {
        route: '/robots.txt',
        handler: '~/server/routes/robots.txt.js',
      },
      {
        route: '/sitemap.xml',
        handler: '~/server/routes/sitemap.xml.js',
      }
    ]
  },
  vite: {
    server: {
      hmr: {
        overlay: false,
      },
      watch: {
        usePolling: false,
        ignored: ['**/node_modules/**', '**/.git/**'],
      },
      fs: {
        strict: true,
      },
    },
  },
  compatibilityDate: '2024-11-01',
  devtools: {enabled: false},

  // Disable server-side rendering
  ssr: process.env.SSR === 'true',

  modules: ['@pinia/nuxt', '@nuxtjs/i18n', '@vite-pwa/nuxt'],

  // Global page headers
  app: {
    head: {

      htmlAttrs: {
        lang: 'en'
      },
      meta: [
        {charset: 'utf-8'},
        {name: 'viewport', content: 'width=device-width, initial-scale=1'},
        {hid: 'description', name: 'description', content: ''},
        {name: 'format-detection', content: 'telephone=no'}
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/favicon.png' },
        { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
        { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' },
        { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap' }
      ],
      script: [
        { src: 'https://cdn.tailwindcss.com' },
        {
          children: `
            tailwind.config = {
              theme: {
                extend: {
                  colors: {
                    primary: {
                      DEFAULT: '#1D4ED8', // Logo Blue
                      50: '#eff6ff',
                      100: '#dbeafe',
                      200: '#bfdbfe',
                      300: '#93c5fd',
                      400: '#60a5fa',
                      500: '#3b82f6',
                      600: '#1D4ED8', 
                      700: '#1e40af',
                      800: '#1e3a8a',
                      900: '#1e3a8a',
                    },
                    secondary: {
                      DEFAULT: '#3B82F6', // Tech Shop Blue
                    },
                    accent: {
                      DEFAULT: '#1F2937', // Charcoal Black
                    },
                    silver: {
                      DEFAULT: '#E5E7EB',
                    }
                  }
                }
              }
            }
          `
        }
      ]
    }
  },
  css: [
    '~/assets/styles/styles.styl',
  ],
  runtimeConfig: {
    public: {
      apiBase: process.env.API_BASE || 'http://127.0.0.1:8000/',
      auth_token_key: 'ishop_frontend_auth',
    }
  },
  components: true,
  i18n: {
    compilation: {
      strictMessage: false,
    },
    locales: [
      {code: 'en',},
      {code: 'fr',},
      {code: 'ar'},
      {code: 'tr',},
      {code: 'hi',},
    ],
    lazy: true,
    vueI18n: '~/lang/config.js',
    strategy: 'no_prefix',
    detectBrowserLanguage: false,
    defaultLocale: null
  },

  pwa: {
    manifest: {
      name: process.env.APP_NAME,
      short_name: process.env.APP_NAME,
      theme_color:'#000000',
      description: "An ecommerce app",
      icons: [
        {
          src: 'pwa-icon.png',
          sizes: "150x150",
          type: "image/png"
        },
      ]
    },
    workbox: {
      navigateFallback: null,
    },
    devOptions: {
      enabled: false,
      type: "module"
    }
  },
  // Build Configuration
  build: {
    transpile: []
  },
})
