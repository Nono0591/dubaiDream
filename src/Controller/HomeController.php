<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\CategoryRepository;
use App\Repository\HeaderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ReviewRepository;
use App\Repository\BrandRepository;

final class HomeController extends AbstractController
{
#[Route('/', name: 'app_home')]
public function index( HeaderRepository $headerRepository, ProductRepository $productRepository, ReviewRepository $reviewRepository, CategoryRepository $categoryRepository, BrandRepository $brandRepository, Cart $cart): Response
{
    $products = $productRepository->findAll();

    return $this->render('home/index.html.twig', [
        'headers' => $headerRepository->findAll(),
        'productsInHomepage' => $productRepository->findBy(['isHomepage' => true]),
        'products' => $products,
        'categories' => $categoryRepository->findAll(),
        'brands' => $brandRepository->findAll(), 
        'cart' => $cart->getCart(),
        'fullCartquantity' => $cart->fullQuantity(),
        'totalWt' => $cart->getTotalWt(),
        'reviews' => $reviewRepository->findBy(
            ['isVisible' => true],
            ['position' => 'ASC']
        ),
       
    ]);
}
}