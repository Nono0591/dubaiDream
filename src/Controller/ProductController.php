<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    /**
     * Page liste / catalogue de TOUS les produits
     * Route demandée par : _navbar.html.twig, _popular_products.html.twig, _bestsellers.html.twig
     */
    #[Route('/produits', name: 'app_products')]
    public function list(ProductRepository $productRepository): Response
    {
        return $this->render('product/list.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * Page FICHE PRODUIT d'un seul produit
     * Route demandée par : _product_card.html.twig
     */
    #[Route('/produit/{slug}', name: 'app_product')]
    public function index(string $slug, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBySlug($slug);

        if (!$product) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }
}