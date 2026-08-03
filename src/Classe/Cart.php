<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;


class Cart
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function add($product)
    {
        // Appeler la session de Symfony
        $cart = $this->getCart();

        // Ajouter une quantity +1 à mon produit
        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'object' => $product,
                'quantity' => $cart[$product->getId()]['quantity'] + 1
            ];
        } else {
            $cart[$product->getId()] = [
                'object' => $product,
                'quantity' => 1
            ];
        }
        
        // Créer ou mettre à jour la session Cart
        $this->requestStack->getSession()->set('cart', $cart);
    }
        // Permet de diminuer la quantité d'un produit dans le panier
        public function decrease($id)
        {
         
            $cart = $this->getCart();
    
            if ($cart[$id]['quantity']> 1 ) {
                $cart[$id]['quantity'] = $cart[$id]['quantity'] -1;  
            }else {
                unset($cart[$id]);
            }
            
    
            // Créer ou mettre à jour la session Cart
            $this->requestStack->getSession()->set('cart', $cart);
        }
    
        // Permet de calculer la quantité total du panier
        public function fullQuantity()
        {
            $cart = $this->getCart();
            $quantity = 0;

            if (!isset($cart)) {
                return $quantity;
            }

            foreach($cart as $product){
                $quantity = $quantity + $product['quantity'];
            }
            return $quantity;
        }

        // Permet de calculer le prix total du panier

        public function getTotalWt (){
        
            $cart = $this->getCart();
            $price = 0;

            if(!isset($cart)){
                return $price;
            }

            foreach($cart as $product){
                $price = $price + ($product['object']->getPriceWt() * $product['quantity']);
            }
            return $price;


        }
        // Permet de supprimer le panier
        public function remove()
        {
            return $this->requestStack->getSession()->remove('cart', []);
        }
        // Permet de récupérer le panier
        public function getCart()
        {
            return $this->requestStack->getSession()->get('cart', []);
        }

}