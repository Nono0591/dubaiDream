<?php

namespace App\Controller;

use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BrandController extends AbstractController
{
    #[Route('/marques', name: 'app_brands')]
    public function index(BrandRepository $brandRepository): Response
    {
        return $this->render('home/_brands.html.twig', [
            'brands' => $brandRepository->findAll(),
        ]);
    }
}