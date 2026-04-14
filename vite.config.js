import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js',
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    build: {
        // Optimize chunk size for faster loading
        chunkSizeWarningLimit: 500,
        rollupOptions: {
            output: {
                manualChunks: (id) => {
                    // Separate vendor chunks for better caching
                    if (id.includes('node_modules')) {
                        if (id.includes('vue') || id.includes('@inertiajs')) {
                            return 'vue-vendor';
                        }
                        if (id.includes('lucide-vue-next')) {
                            return 'icons';
                        }
                        return 'vendor';
                    }
                },
            },
        },
        // Enable minification for production (uses esbuild by default)
        minify: 'esbuild',
        target: 'esnext',
    },
    optimizeDeps: {
        // Pre-bundle commonly used dependencies
        include: ['vue', '@inertiajs/vue3', 'lucide-vue-next', 'axios'],
    },
});
