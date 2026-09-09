import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                // Serif per i titoli: dà all'app il carattere editoriale di una libreria
                serif: ['Lora', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                // Palette ispirata ai mockup "Foxglove Books": crema calda + terracotta + oliva.
                // gray/indigo/green sono gli unici colori "di sistema" usati nell'app, quindi
                // ridefinirli qui propaga lo stile ovunque senza toccare ogni singola view.
                gray: {
                    50: '#FAF5EA',
                    100: '#F3EBD9',
                    200: '#E6D8BC',
                    300: '#D3BE95',
                    400: '#B99C6C',
                    500: '#96794F',
                    600: '#785F3C',
                    700: '#5C482E',
                    800: '#413320',
                    900: '#2B2114',
                    950: '#19130B',
                },
                indigo: {
                    50: '#FBEFE9',
                    100: '#F6DCCE',
                    200: '#ECB89C',
                    300: '#E19269',
                    400: '#D2723F',
                    500: '#C1623A',
                    600: '#A9502D',
                    700: '#874025',
                    800: '#65301C',
                    900: '#442013',
                    950: '#2B140C',
                },
                green: {
                    50: '#F1F3E9',
                    100: '#E1E7CE',
                    200: '#C4D0A0',
                    300: '#A4B673',
                    400: '#889C55',
                    500: '#6F8341',
                    600: '#5A6934',
                    700: '#46512A',
                    800: '#333B1F',
                    900: '#212614',
                    950: '#14170C',
                },
            },
        },
    },

    plugins: [forms],
};
