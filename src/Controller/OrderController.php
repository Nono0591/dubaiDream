<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class OrderController extends AbstractController
{
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        /** @var \App\Entity\User $user */
        $addresses = $this->getUser()->getAddresses();

        if (count($addresses) == 0) {
            return $this->redirectToRoute('app_account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_order_summary')
        ]);

        return $this->render('order/index.html.twig', [
            'deliverForm' => $form->createView(),
        ]);
    }

    // recap de la commande de l'utilisateur 
    // insertion en base de donnée

    #[Route('/commande/recapitulatif', name: 'app_order_summary', methods: ['POST', 'GET'])]
    public function add(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {

        if ($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_cart');
        }

        $products = $cart->getCart();

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(),

        ]);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $addressObj = $form->get('addresses')->getData();

            $address = $addressObj->getFirstname() . ' ' . $addressObj->getLastname() . '<br/>';
            $address .= $addressObj->getAddress() . '<br/>';
            $address .= $addressObj->getPostcode() . ' ' . $addressObj->getCity() . '<br/>';
            $address .= $addressObj->getCountry() . '<br/>';
            $address .= $addressObj->getPhone();

            // Stocker les infos en bdd
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime());
            $order->setState(1);
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
            $order->setDelivery($address);
        }
            foreach ($products as $product) {
                $orderDetail = new OrderDetail();
                $orderDetail->setProductName($product['object']->getName());
                $orderDetail->setProductIllustration($product['object']->getIllustration());
                $orderDetail->setProductPrice($product['object']->getPrice());
                $orderDetail->setProductTva($product['object']->getTva());
                $orderDetail->setProductQuantity($product['quantity']);
                $order->addOrderDetail($orderDetail);
            }

                $entityManager->persist($order);
                $entityManager->flush();
        


        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $products,
            'order' => $order,
            'totalWt'=> $cart->getTotalWt()
        ]);
    }
}
