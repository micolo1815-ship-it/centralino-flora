import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            // Vite processes this file...
            input: ['resources/css/style.css'],
            // ...and puts it in public/build/assets/style.css
        }),
        tailwindcss(),
    ],
});
