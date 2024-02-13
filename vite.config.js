import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                    'resources/css/404.css',
                    'resources/css/adminlte.min.css',
                    'resources/css/bootstrap.min.css',
                    'resources/css/template.css',
                    'resources/css/font-awesome.min.css',
                    'resources/js/all.min.js',
                    'resources/js/app.js',
                    'resources/js/blockUserModal.js',
                    'resources/js/bootstrap.min.js',
                    'resources/js/confirmAlert.js',
                    'resources/js/courseModal.js',
                    'resources/js/discountModal.js',
                    'resources/js/heartBeat.js',
                    'resources/js/loader.js'],
            refresh: true,
        }),
    ],
});