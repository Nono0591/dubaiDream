<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/admin/order/{id}', name: 'admin_order_show')]
    public function show(Order $order): Response
    {
        return $this->render('admin/order.html.twig', [
            'order' => $order,
        ]);
    }
}