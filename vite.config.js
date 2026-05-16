import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/style.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
    manifest: 'manifest.json', // force le manifest à la racine du build
},
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
