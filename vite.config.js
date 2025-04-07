import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import ip from 'ip';

const host= ip.address().startsWith("172.") ? "194.87.238.106" : "localhost";

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: host,
            port: 80,  // Порт Nginx
            protocol: 'ws',  // WebSocket
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
});
