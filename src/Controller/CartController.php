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
    public function index(
        Cart $cart,
        ?string $motif = null
    ): Response {

        if ($motif === 'annulation') {

            $this->addFlash(
                'info',
                'Vous pouvez mettre à jour votre panier et votre commande.'
            );

        }


        $totalWt = $cart->getTotalWt();

        $isShippingFree = $totalWt >= self::SHIPPING_THRESHOLD;


        return $this->render('cart/index.html.twig', [

            'cart' => $cart->getCart(),

            'fullCartQuantity' => $cart->fullQuantity(),

            'totalWt' => $totalWt,

            'shippingCost' => $isShippingFree ? 0 : self::SHIPPING_COST,

            'isShippingFree' => $isShippingFree,

            'amountLeftForFreeShipping' => max(
                0,
                self::SHIPPING_THRESHOLD - $totalWt
            ),

        ]);
    }




    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['GET','POST'])]
    public function add(
        int $id,
        Cart $cart,
        ProductRepository $productRepository,
        Request $request
    ): Response {

        $product = $productRepository->find($id);


        if (!$product) {
            throw $this->createNotFoundException();
        }


        $cart->add($product);


        $referer = $request->headers->get('referer');


        return $this->redirect(
            $referer ?? $this->generateUrl('app_cart')
        );
    }





    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease(
        int $id,
        Cart $cart
    ): Response {

        $cart->decrease($id);


        return $this->redirectToRoute('app_cart');
    }





    #[Route('/cart/delete/{id}', name: 'app_cart_delete')]
    public function delete(
        int $id,
        Cart $cart
    ): Response {


        $cart->delete($id);


        return $this->redirectToRoute('app_cart');

    }





    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(
        Cart $cart
    ): Response {


        $cart->remove();


        $this->addFlash(
            'info',
            'Votre panier a été entièrement vidé.'
        );


        return $this->redirectToRoute('app_home');

    }






    /*
    |--------------------------------------------------------------------------
    | Mise à jour panneau latéral
    |--------------------------------------------------------------------------
    */

    #[Route('/cart/offcanvas/{action}/{id}', name: 'app_cart_offcanvas_update')]
    public function offcanvasUpdate(
        string $action,
        int $id,
        Cart $cart,
        ProductRepository $productRepository
    ): Response {


        switch ($action) {


            case 'increase':

                $product = $productRepository->find($id);

                if (!$product) {
                    throw $this->createNotFoundException();
                }

                $cart->add($product);

                break;



            case 'decrease':

                $cart->decrease($id);

                break;



            case 'delete':

                $cart->delete($id);

                break;

        }



        $totalWt = $cart->getTotalWt();



        return $this->render(
            'cart/_cart_content.html.twig',
            [

                'cart' => $cart->getCart(),

                'fullCartQuantity' => $cart->fullQuantity(),

                'totalWt' => $totalWt,


                'isShippingFree' =>
                    $totalWt >= self::SHIPPING_THRESHOLD,


                'amountLeftForFreeShipping' =>
                    max(
                        0,
                        self::SHIPPING_THRESHOLD - $totalWt
                    ),

            ]
        );

    }

}