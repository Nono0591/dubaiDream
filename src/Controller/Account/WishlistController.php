<?php

namespace App\Controller\Account;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WishlistController extends AbstractController
{
    #[Route('/compte/liste-de-souhait', name: 'app_account_wishlist')]
    public function index(): Response
    {
        return $this->render('account/wishlist/index.html.twig', []);
    }

    #[Route('/compte/liste-de-souhait/add/{id}', name: 'app_account_wishlist_add')]
    public function add(ProductRepository $productRepository, int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $product = $productRepository->findOneById(['id' => $id]);

        if ($product) {
            $this->getUser()->addWishlist($product);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Le produit a été ajouté à votre liste de souhaits.');

        return $this->redirect($request->headers->get('referer'));
    }


    #[Route('/compte/liste-de-souhait/remove/{id}', name: 'app_account_wishlist_remove')]
    public function remove(ProductRepository $productRepository, int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $product = $productRepository->findOneById(['id' => $id]);

        if ($product) {

            $this->addFlash('success', 'Le produit a été retiré de votre liste de souhaits.');

            $this->getUser()->removeWishlist($product);
            $entityManager->flush();
        } else {
            $this->addFlash('error', 'Le produit n\'a pas été trouvé dans votre liste de souhaits.');
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
