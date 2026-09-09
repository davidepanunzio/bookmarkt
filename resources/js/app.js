import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Rivela con una piccola animazione le sezioni marcate con [data-reveal] quando entrano nello schermo
document.addEventListener('DOMContentLoaded', () => {
    const sezioni = document.querySelectorAll('[data-reveal]');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    sezioni.forEach((sezione) => observer.observe(sezione));
});
