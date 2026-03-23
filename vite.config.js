import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/style.css'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        rollupOptions: {
            output: {
                // This forces the file name to be exactly "style.css" 
                // so your asset() link doesn't break
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name === 'style.css') return 'style.css';
                    return 'assets/[name]-[hash][extname]';
                },
            },
        },
    },
});
