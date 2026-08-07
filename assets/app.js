import './stimulus_bootstrap.js';
import './styles/app.css';
import './styles/home.css';

console.log('Dubai Dream 🎉');

/*
============================================
PANIER AJAX
============================================
*/
// Délégation sur document : à attacher une seule fois, DOMContentLoaded suffit
document.addEventListener('DOMContentLoaded', () => {

    document.addEventListener('click', function (e) {

        const button = e.target.closest('.cart-update');

        if (!button) {
            return;
        }

        e.preventDefault();

        fetch(button.dataset.url)
            .then(response => response.text())
            .then(html => {

                const cartContent = document.querySelector('#cart-content');

                if (cartContent) {
                    cartContent.innerHTML = html;
                }

                const data = document.querySelector('#cart-data');

                if (data) {

                    const quantity = data.dataset.quantity;

                    const navbarBadge = document.querySelector('.cart-navbar-badge');

                    if (navbarBadge) {
                        navbarBadge.textContent = quantity;
                    }

                    const offcanvasBadge = document.querySelector('.cart-offcanvas-badge');

                    if (offcanvasBadge) {
                        offcanvasBadge.textContent = quantity;
                    }
                }

            })
            .catch(error => {
                console.error('Erreur panier :', error);
            });

    });

});

/*
============================================
ANIMATIONS AU SCROLL
============================================
*/
// turbo:load se déclenche au 1er chargement ET à chaque navigation Turbo
document.addEventListener('turbo:load', () => {

    const observer = new IntersectionObserver((entries, obs) => {

        entries.forEach(entry => {

            if (entry.isIntersecting) {

                if (entry.target.classList.contains('dd-product-item')) {
                    entry.target.classList.add('is-visible');
                } else {
                    entry.target.classList.add('visible');
                }

                obs.unobserve(entry.target);

            }

        });

    }, {
        threshold: 0.15
    });

    document.querySelectorAll('.dd-fade-up, .dd-fade, .dd-product-item').forEach((element) => {
        observer.observe(element);
    });

});