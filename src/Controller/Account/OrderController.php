<?php

namespace App\Controller\Account;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    // Liste de toutes les commandes de l'utilisateur
    #[Route('/compte/commandes', name: 'app_account_order')]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy([
            'user' => $this->getUser()
        ], ['createdAt' => 'DESC']);

        return $this->render('account/order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    // Détail d'une commande précise
    #[Route('/compte/commande/{id_order}', name: 'app_account_order_show')]
    public function show($id_order, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->findOneBy([
            'id' => $id_order,
            'user' => $this->getUser()
        ]);

        if (!$order) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('account/order/show.html.twig', [
            'order' => $order,
        ]);
    }
}