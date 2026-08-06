import './stimulus_bootstrap.js';
import './styles/app.css';
import './styles/home.css';


console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
document.addEventListener('click', function(e) {
    const button = e.target.closest('.cart-update');
    if (!button) {
        return;
    }
    e.preventDefault();
    fetch(button.dataset.url)

        .then(response => response.text())
        .then(html => {
            const cartContent = document.querySelector('#cart-content');
            if(cartContent){

                cartContent.innerHTML = html;

            }
            // Récupération de la nouvelle quantité

            const data = document.querySelector('#cart-data');
            if(data){
                const quantity = data.dataset.quantity;

                // ==========================
                // Badge navbar
                // ==========================

                const navbarBadge = document.querySelector('.cart-navbar-badge');
                if(navbarBadge){

                    navbarBadge.textContent = quantity;

                }
                // ==========================
                // Badge offcanvas
                // ==========================

                const offcanvasBadge = document.querySelector('.cart-offcanvas-badge');

                if(offcanvasBadge){

                    offcanvasBadge.textContent = quantity;

                }
            }
        })
        .catch(error => {
            console.error(
                'Erreur mise à jour panier :',
                error
            );
        });



});