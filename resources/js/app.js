import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import ujs from '@rails/ujs'

document.addEventListener("DOMContentLoaded", () => {
    ujs.start()
});
