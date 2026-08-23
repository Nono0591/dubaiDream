import './stimulus_bootstrap.js';
import './styles/app.css';
import './styles/home.css';
import 'bootstrap';

console.log('Dubai Dream 🎉');

/*
============================================
PANIER AJAX
============================================
*/
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
    }, { threshold: 0.15 });

    document.querySelectorAll('.dd-fade-up, .dd-fade, .dd-product-item, .dd-reveal').forEach((element) => {
        observer.observe(element);
    });

});

(function () {
    if (navigator.userAgent.includes('Firefox')) {
        document.documentElement.classList.add('is-firefox');
    }
})();


/*
============================================
GRILLE TIKTOK — lecture vidéo au survol
============================================
*/
function initTiktokGrid() {

    const grid = document.querySelector('#tiktok-grid');

    if (!grid) {
        return;
    }

    grid.addEventListener('mouseenter', function (e) {

        const card = e.target.closest('.dd-social-card');

        if (!card || !grid.contains(card)) {
            return;
        }

        if (card.querySelector('.dd-social-iframe')) {
            return;
        }

        const videoUrl = card.dataset.videoUrl;
        const match = videoUrl && videoUrl.match(/\/video\/(\d+)/);

        if (!match) {
            return;
        }

        const videoId = match[1];

        const iframe = document.createElement('iframe');
        iframe.src = `https://www.tiktok.com/embed/v2/${videoId}?autoplay=1&muted=1&loop=1&controls=0`;
        iframe.allow = 'autoplay; encrypted-media';
        iframe.setAttribute('frameborder', '0');
        iframe.className = 'dd-social-iframe';
        card.appendChild(iframe);

    }, true);

    grid.addEventListener('mouseleave', function (e) {

        const card = e.target.closest('.dd-social-card');

        if (!card || !grid.contains(card)) {
            return;
        }

        const iframe = card.querySelector('.dd-social-iframe');

        if (iframe) {
            iframe.remove();
        }

    }, true);

    grid.addEventListener('click', function (e) {

        const card = e.target.closest('.dd-social-card');

        if (!card || !grid.contains(card)) {
            return;
        }

        const videoUrl = card.dataset.videoUrl;

        if (videoUrl) {
            window.open(videoUrl, '_blank', 'noopener');
        }

    });

}

document.addEventListener('DOMContentLoaded', initTiktokGrid);
document.addEventListener('turbo:load', initTiktokGrid);