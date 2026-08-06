<?php

namespace App\EventSubscriber;

use App\Classe\Cart;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class CartSubscriber implements EventSubscriberInterface
{
    private Environment $twig;
    private Cart $cart;

    public function __construct(Environment $twig, Cart $cart)
    {
        $this->twig = $twig;
        $this->cart = $cart;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $this->twig->addGlobal('cart', $this->cart->getCart());
        $this->twig->addGlobal('fullCartQuantity', $this->cart->fullQuantity());
        $this->twig->addGlobal('totalWt', $this->cart->getTotalWt());

        $isShippingFree = $this->cart->getTotalWt() >= 60;
        $shippingCost = $isShippingFree ? 0 : 3.90;
        $amountLeft = max(0, 60 - $this->cart->getTotalWt());

        $this->twig->addGlobal('shippingCost', $shippingCost);
        $this->twig->addGlobal('isShippingFree', $isShippingFree);
        $this->twig->addGlobal('amountLeftForFreeShipping', $amountLeft);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}