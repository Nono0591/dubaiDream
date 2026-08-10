<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Entity\Carrier;
use App\Entity\Category;
use App\Entity\Header;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\User;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
        private BrandRepository $brandRepository,
        private OrderRepository $orderRepository,
        private UserRepository $userRepository,
        private ReviewRepository $reviewRepository
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $stats = [
            'products'   => $this->productRepository->count([]),
            'categories' => $this->categoryRepository->count([]),
            'brands'     => $this->brandRepository->count([]),
            'orders'     => $this->orderRepository->count([]),
            'users'      => $this->userRepository->count([]),
            'reviews'    => $this->reviewRepository->count([]),
        ];

        return $this->render('admin/dashboard.html.twig', [
            'stats' => $stats,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<strong>Dubai Dream</strong> Administration');
    }

    public function configureAssets(): Assets
{
    return Assets::new()
        ->addCssFile('assets/css/easyadmin.css');
}
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fas fa-gauge-high');
        yield MenuItem::section('Catalogue');
        yield MenuItem::linkToCrud('Produits', 'fas fa-box', Product::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Marques', 'fas fa-tags', Brand::class);
        yield MenuItem::section('Commandes');
        yield MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('Transporteurs', 'fas fa-truck', Carrier::class);
        yield MenuItem::section('Contenu');
        yield MenuItem::linkToCrud('Header', 'fas fa-images', Header::class);
        yield MenuItem::linkToCrud('Avis clients', 'fas fa-star', Review::class);
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Clients', 'fas fa-users', User::class);
        yield MenuItem::section('Site');
        yield MenuItem::linkToRoute('Retour au site', 'fas fa-house', 'app_home');
        yield MenuItem::linkToLogout('Déconnexion', 'fas fa-right-from-bracket');
    }
}