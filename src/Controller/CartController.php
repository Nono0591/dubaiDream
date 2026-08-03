<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    private const SHIPPING_THRESHOLD = 60.0;
    private const SHIPPING_COST = 3.90;

    #[Route('/mon-panier/{motif}', name: 'app_cart', defaults: ['motif' => null])]
    public function index(Cart $cart, ?string $motif = null): Response
    {
        if ($motif === 'annulation') {
            $this->addFlash(
                'info',
                'Vous pouvez mettre à jour votre panier et votre commande.'
            );
        }

        $totalWt = $cart->getTotalWt();
        $isShippingFree = $totalWt >= self::SHIPPING_THRESHOLD;
        $shippingCost = $isShippingFree ? 0.0 : self::SHIPPING_COST;
        $amountLeftForFreeShipping = max(0.0, self::SHIPPING_THRESHOLD - $totalWt);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(),
            'fullCartQuantity' => $cart->fullQuantity(),
            'totalWt' => $totalWt,
            'shippingCost' => $shippingCost,
            'isShippingFree' => $isShippingFree,
            'amountLeftForFreeShipping' => $amountLeftForFreeShipping,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['GET', 'POST'])]
    public function add(int $id, Cart $cart, ProductRepository $productRepository, Request $request): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit recherché n'existe pas.");
        }

        $cart->add($product);

        $this->addFlash(
            'success',
            'Le produit a bien été ajouté à votre panier.'
        );

        $referer = $request->headers->get('referer');

        return $this->redirect($referer ?? $this->generateUrl('app_cart'));
    }

    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease(int $id, Cart $cart, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit recherché n'existe pas.");
        }

        $cart->decrease($id);

        $this->addFlash(
            'success',
            'La quantité du produit a bien été mise à jour.'
        );

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/delete/{id}', name: 'app_cart_delete')]
    public function delete(int $id, Cart $cart): Response
    {
        $cart->delete($id);

        $this->addFlash(
            'info',
            'Le produit a été retiré de votre panier.'
        );

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();

        $this->addFlash(
            'info',
            'Votre panier a été entièrement vidé.'
        );

        return $this->redirectToRoute('app_home');
    }
}