import { defineConfig } from 'vite'
import laravel, { refreshPaths } from 'laravel-vite-plugin'
import path from 'path'

/** @type {import('vite').UserConfig} */
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/bladepack.ts'],
            refresh: [
                ...refreshPaths,
                'app/Http/Livewire/**',
            ],
        }),
    ],
    // server: {
    //     host: '127.0.0.1'
    // }
})
