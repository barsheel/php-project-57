import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
// чтобы на проде генерировались HTTPS-ссылки к ассетам
    base: '[https://php-project-57-ibuh.onrender.com/](https://php-project-57-ibuh.onrender.com/)',
});
