import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            // Ensure these paths match your resources folder
            input: ['resources/css/style.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
